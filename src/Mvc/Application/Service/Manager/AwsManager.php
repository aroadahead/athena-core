<?php

namespace AthenaCore\Mvc\Application\Service\Manager;

use AthenaCore\Mvc\Application\Config\Manager\ConfigManager;
use Aws\DynamoDb\DynamoDbClient;
use Aws\Ec2\Ec2Client;
use Aws\Iam\IamClient;
use Aws\Kinesis\KinesisClient;
use Aws\Kms\KmsClient;
use Aws\MediaConvert\MediaConvertClient;
use Aws\OpenSearchService\OpenSearchServiceClient;
use Aws\S3\S3Client;

//https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide
class AwsManager
{
    protected ?Ec2Client $ec2Client = null;
    protected ?S3Client $s3Client = null;
    protected ?KinesisClient $kinesisClient = null;
    protected ?DynamoDbClient $dynamoDbClient = null;
    protected ?IamClient $iamClient = null;
    protected ?OpenSearchServiceClient $openSearchServiceClient = null;
    protected ?KmsClient $kmsClient = null;
    protected ?MediaConvertClient $mediaConvertClient;
    protected ?ConfigManager $configManager = null;

    public function mediaConvert(): MediaConvertClient
    {
        if ($this -> mediaConvertClient === null) {
            $this -> mediaConvertClient = new MediaConvertClient($this -> getConfig('mediaconvert'));
        }
        return $this -> mediaConvertClient;
    }

    public function kinesis(): KinesisClient
    {
        if ($this -> kinesisClient === null) {
            $this -> kinesisClient = new KinesisClient($this -> getConfig('kinesis'));
        }
        return $this -> kinesisClient;
    }

    public function kms(): KmsClient
    {
        if ($this -> kmsClient === null) {
            $this -> kmsClient = new KmsClient($this -> getConfig('kms'));
        }
        return $this -> kmsClient;
    }

    public function iam(): IamClient
    {
        if ($this -> iamClient === null) {
            $this -> iamClient = new IamClient($this -> getConfig('iam'));
        }
        return $this -> iamClient;
    }

    public function openSearch(): OpenSearchServiceClient
    {
        if ($this -> openSearchServiceClient === null) {
            $this -> openSearchServiceClient = new OpenSearchServiceClient($this -> getConfig('opensearch'));
        }
        return $this -> openSearchServiceClient;
    }

    public function dynamoDb(): DynamoDbClient
    {
        if ($this -> dynamoDbClient === null) {
            $this -> dynamoDbClient = new DynamoDbClient($this -> getConfig('dynamodb'));
        }
        return $this -> dynamoDbClient;
    }

    public function s3(): S3Client
    {
        if ($this -> s3Client === null) {
            $this -> s3Client = new S3Client($this -> getConfig('s3'));
        }
        return $this -> s3Client;
    }

    public function ec2(): Ec2Client
    {
        if ($this -> ec2Client === null) {
            $this -> ec2Client = new Ec2Client($this -> getConfig('ec2'));
        }
        return $this -> ec2Client;
    }

    public function setConfigManager(ConfigManager $configManager): void
    {
        $this -> configManager = $configManager;
    }

    private function getConfig(string $client): array
    {
        return $this -> configManager -> lookup("services.aws.{$client}");
    }
}