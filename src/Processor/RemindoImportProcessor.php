<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Processor;

use App\Contract\FileParser\FileParserFactoryInterface;
use App\Doctrine\Exception\DoctrineTransactionException;
use App\Entity\Question;
use App\Entity\Remindo;
use App\Entity\Respondent;
use App\Entity\Result;
use App\Exception\FileParser\FileParseException;
use App\Exception\ProcessorException;
use App\Form\Data\UploadData;
use App\Validation\RemindoImportValidator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

/**
 * Remindo Import Processor.
 *
 * @author wicliff <wwolda@gmail.com>
 */
final class RemindoImportProcessor
{
    private const ROW_QUESTIONS = 1;
    private const ROW_RESPONDENTS = 2;

    private const COL_RESPONDENT_NAME = 0;
    private const COL_RESPONDENT_RESULTS = 1;
    private const COL_QUESTION_MAX = 1;

    private const BATCH_SIZE_RESPONDENT = 50;

    /**
     * @var \App\FileParser\FileParserFactory
     */
    private FileParserFactoryInterface $parserFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var \App\Validation\RemindoImportValidator
     */
    private RemindoImportValidator $validator;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface|\App\Doctrine\Decorator\TransactionalEntityManagerDecorator
     */
    private EntityManagerInterface $em;

    /**
     * @param \App\FileParser\FileParserFactory                           $parserFactory
     * @param \Psr\Log\LoggerInterface                                    $logger
     * @param \App\Validation\RemindoImportValidator                      $validator
     * @param \App\Doctrine\Decorator\TransactionalEntityManagerDecorator $em
     */
    public function __construct(FileParserFactoryInterface $parserFactory, LoggerInterface $logger, RemindoImportValidator $validator, EntityManagerInterface $em)
    {
        $this->parserFactory = $parserFactory;
        $this->logger = $logger;
        $this->validator = $validator;
        $this->em = $em;
    }

    /**
     * @param \App\Form\Data\UploadData $data
     *
     * @return \App\Entity\Remindo
     *
     * @throws \App\Exception\ProcessorException
     */
    public function process(UploadData $data): Remindo
    {
        if (false === $data->isValidUpload()) {
            $this->logger->error('upload is not valid {file}', [
                'file' => $data->getName(),
            ]);

            throw new ProcessorException('invalid upload');
        }

        try {
            $parsed = $this->parserFactory->create($data->getFile())->parse();
        } catch (FileParseException $e) {
            $this->logger->error('unable to create parser for {file}', [
                'file' => $data->getFile()->getFilename(),
                'exception' => FlattenException::createFromThrowable($e),
            ]);

            throw new ProcessorException($e->getMessage(), $e);
        }

        if (false === $this->validator->validate($parsed)) {
            throw new ProcessorException('data does not validate');
        }

        try {
            $test = $this->test($data);
            $test = $this->questions($parsed, $test);
            $test = $this->respondents($parsed, $test);
        } catch (DoctrineTransactionException $e) {
            throw new ProcessorException('flushing error', $e);
        }

        return $test;
    }

    /**
     * @param array<int, mixed>   $data
     * @param \App\Entity\Remindo $test
     *
     * @return \App\Entity\Remindo
     *
     * @throws \App\Doctrine\Exception\DoctrineTransactionException
     */
    private function respondents(array $data, Remindo $test): Remindo
    {
        $count = \count($data);

        for ($respondentRow = self::ROW_RESPONDENTS; $respondentRow < $count; ++$respondentRow) {
            $respondent = Respondent::fromImportData($data[$respondentRow][self::COL_RESPONDENT_NAME], $test);

            $this->results($data[$respondentRow], $test, $respondent);

            $this->em->persist($respondent);

            if (($respondentRow % self::BATCH_SIZE_RESPONDENT) === 0) {
                $test = $this->flush($test);
            }
        }

        return $this->flush($test);
    }

    /**
     * @param array<int, float>      $data
     * @param \App\Entity\Remindo    $test
     * @param \App\Entity\Respondent $respondent
     */
    private function results(array $data, Remindo $test, Respondent $respondent): void
    {
        $resultsCount = \count($data);

        for ($sequence = self::COL_RESPONDENT_RESULTS; $sequence < $resultsCount; ++$sequence) {
            if (false === $question = $test->getQuestionBySequence($sequence)) {
                // this should not happen / be caught by validation
                continue;
            }

            $result = Result::fromImportData((float) $data[$sequence], $respondent, $test, $question);

            $this->em->persist($result);
        }
    }

    /**
     * @param array<int, array>   $data
     * @param \App\Entity\Remindo $test
     *
     * @return \App\Entity\Remindo
     *
     * @throws \App\Doctrine\Exception\DoctrineTransactionException
     */
    private function questions(array $data, Remindo $test): Remindo
    {
        $questionCount = \count($data[self::ROW_QUESTIONS]);

        for ($sequence = self::COL_QUESTION_MAX; $sequence < $questionCount; ++$sequence) {
            $question = Question::fromImportData($sequence, (int) $data[self::ROW_QUESTIONS][$sequence], $test);

            $this->em->persist($question);
        }

        return $this->flush($test);
    }

    /**
     * @param \App\Form\Data\UploadData $data
     *
     * @return \App\Entity\Remindo
     *
     * @throws \App\Doctrine\Exception\DoctrineTransactionException
     */
    private function test(UploadData $data): Remindo
    {
        $test = (new Remindo())
            ->setName($data->getName());

        $this->em->persist($test);
        $this->em->flush();

        return $test;
    }

    /**
     * @param \App\Entity\Remindo $test
     *
     * @return \App\Entity\Remindo
     *
     * @throws \App\Doctrine\Exception\DoctrineTransactionException
     */
    private function flush(Remindo $test): Remindo
    {
        try {
            $this->em->flush();
        } catch (DoctrineTransactionException $e) {
            $this->logger->error(
                'flushing processed entities for {test} failed. aborting import. {message}',
                [
                    'test' => $test->getName(),
                    'message' => $e->getMessage(),
                    'exception' => $e,
                ]
            );

            throw $e;
        }

        $this->em->clear();

        return $this->em->getRepository(Remindo::class)->getEager($test->getId());
    }
}
