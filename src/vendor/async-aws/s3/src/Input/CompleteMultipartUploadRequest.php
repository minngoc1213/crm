<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\RequestPayer;
use AsyncAws\S3\ValueObject\CompletedMultipartUpload;

final class CompleteMultipartUploadRequest extends Input
{
    /**
     * Name of the bucket to which the multipart upload was initiated.
     *
     * **Directory buckets** - When you use this operation with a directory bucket, you must use virtual-hosted-style
     * requests in the format `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`. Path-style requests are not
     * supported. Directory bucket names must be unique in the chosen Availability Zone. Bucket names must follow the format
     * `*bucket_base_name*--*az-id*--x-s3` (for example, `*DOC-EXAMPLE-BUCKET*--*usw2-az1*--x-s3`). For information about
     * bucket naming restrictions, see Directory bucket naming rules [^1] in the *Amazon S3 User Guide*.
     *
     * **Access points** - When you use this action with an access point, you must provide the alias of the access point in
     * place of the bucket name or specify the access point ARN. When using the access point ARN, you must direct requests
     * to the access point hostname. The access point hostname takes the form
     * *AccessPointName*-*AccountId*.s3-accesspoint.*Region*.amazonaws.com. When using this action with an access point
     * through the Amazon Web Services SDKs, you provide the access point ARN in place of the bucket name. For more
     * information about access point ARNs, see Using access points [^2] in the *Amazon S3 User Guide*.
     *
     * > Access points and Object Lambda access points are not supported by directory buckets.
     *
     * **S3 on Outposts** - When you use this action with Amazon S3 on Outposts, you must direct requests to the S3 on
     * Outposts hostname. The S3 on Outposts hostname takes the form
     * `*AccessPointName*-*AccountId*.*outpostID*.s3-outposts.*Region*.amazonaws.com`. When you use this action with S3 on
     * Outposts through the Amazon Web Services SDKs, you provide the Outposts access point ARN in place of the bucket name.
     * For more information about S3 on Outposts ARNs, see What is S3 on Outposts? [^3] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/directory-bucket-naming-rules.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/using-access-points.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/S3onOutposts.html
     *
     * @required
     *
     * @var string|null
     */
    private $bucket;

    /**
     * Object key for which the multipart upload was initiated.
     *
     * @required
     *
     * @var string|null
     */
    private $key;

    /**
     * The container for the multipart upload request information.
     *
     * @var CompletedMultipartUpload|null
     */
    private $multipartUpload;

    /**
     * ID for the initiated multipart upload.
     *
     * @required
     *
     * @var string|null
     */
    private $uploadId;

    /**
     * This header can be used as a data integrity check to verify that the data received is the same data that was
     * originally sent. This header specifies the base64-encoded, 32-bit CRC-32 checksum of the object. For more
     * information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumCrc32;

    /**
     * This header can be used as a data integrity check to verify that the data received is the same data that was
     * originally sent. This header specifies the base64-encoded, 32-bit CRC-32C checksum of the object. For more
     * information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumCrc32C;

    /**
     * This header can be used as a data integrity check to verify that the data received is the same data that was
     * originally sent. This header specifies the base64-encoded, 160-bit SHA-1 digest of the object. For more information,
     * see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumSha1;

    /**
     * This header can be used as a data integrity check to verify that the data received is the same data that was
     * originally sent. This header specifies the base64-encoded, 256-bit SHA-256 digest of the object. For more
     * information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumSha256;

    /**
     * @var RequestPayer::*|null
     */
    private $requestPayer;

    /**
     * The account ID of the expected bucket owner. If the account ID that you provide does not match the actual owner of
     * the bucket, the request fails with the HTTP status code `403 Forbidden` (access denied).
     *
     * @var string|null
     */
    private $expectedBucketOwner;

    /**
     * Uploads the object only if the ETag (entity tag) value provided during the WRITE operation matches the ETag of the
     * object in S3. If the ETag values do not match, the operation returns a `412 Precondition Failed` error.
     *
     * If a conflicting operation occurs during the upload S3 returns a `409 ConditionalRequestConflict` response. On a 409
     * failure you should fetch the object's ETag, re-initiate the multipart upload with `CreateMultipartUpload`, and
     * re-upload each part.
     *
     * Expects the ETag value as a string.
     *
     * For more information about conditional requests, see RFC 7232 [^1], or Conditional requests [^2] in the *Amazon S3
     * User Guide*.
     *
     * [^1]: https://tools.ietf.org/html/rfc7232
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/conditional-requests.html
     *
     * @var string|null
     */
    private $ifMatch;

    /**
     * Uploads the object only if the object key name does not already exist in the bucket specified. Otherwise, Amazon S3
     * returns a `412 Precondition Failed` error.
     *
     * If a conflicting operation occurs during the upload S3 returns a `409 ConditionalRequestConflict` response. On a 409
     * failure you should re-initiate the multipart upload with `CreateMultipartUpload` and re-upload each part.
     *
     * Expects the '*' (asterisk) character.
     *
     * For more information about conditional requests, see RFC 7232 [^1], or Conditional requests [^2] in the *Amazon S3
     * User Guide*.
     *
     * [^1]: https://tools.ietf.org/html/rfc7232
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/conditional-requests.html
     *
     * @var string|null
     */
    private $ifNoneMatch;

    /**
     * The server-side encryption (SSE) algorithm used to encrypt the object. This parameter is required only when the
     * object was created using a checksum algorithm or if your bucket policy requires the use of SSE-C. For more
     * information, see Protecting data using SSE-C keys [^1] in the *Amazon S3 User Guide*.
     *
     * > This functionality is not supported for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/ServerSideEncryptionCustomerKeys.html#ssec-require-condition-key
     *
     * @var string|null
     */
    private $sseCustomerAlgorithm;

    /**
     * The server-side encryption (SSE) customer managed key. This parameter is needed only when the object was created
     * using a checksum algorithm. For more information, see Protecting data using SSE-C keys [^1] in the *Amazon S3 User
     * Guide*.
     *
     * > This functionality is not supported for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/ServerSideEncryptionCustomerKeys.html
     *
     * @var string|null
     */
    private $sseCustomerKey;

    /**
     * The MD5 server-side encryption (SSE) customer managed key. This parameter is needed only when the object was created
     * using a checksum algorithm. For more information, see Protecting data using SSE-C keys [^1] in the *Amazon S3 User
     * Guide*.
     *
     * > This functionality is not supported for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/ServerSideEncryptionCustomerKeys.html
     *
     * @var string|null
     */
    private $sseCustomerKeyMd5;

    /**
     * @param array{
     *   Bucket?: string,
     *   Key?: string,
     *   MultipartUpload?: null|CompletedMultipartUpload|array,
     *   UploadId?: string,
     *   ChecksumCRC32?: null|string,
     *   ChecksumCRC32C?: null|string,
     *   ChecksumSHA1?: null|string,
     *   ChecksumSHA256?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   ExpectedBucketOwner?: null|string,
     *   IfMatch?: null|string,
     *   IfNoneMatch?: null|string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->bucket = $input['Bucket'] ?? null;
        $this->key = $input['Key'] ?? null;
        $this->multipartUpload = isset($input['MultipartUpload']) ? CompletedMultipartUpload::create($input['MultipartUpload']) : null;
        $this->uploadId = $input['UploadId'] ?? null;
        $this->checksumCrc32 = $input['ChecksumCRC32'] ?? null;
        $this->checksumCrc32C = $input['ChecksumCRC32C'] ?? null;
        $this->checksumSha1 = $input['ChecksumSHA1'] ?? null;
        $this->checksumSha256 = $input['ChecksumSHA256'] ?? null;
        $this->requestPayer = $input['RequestPayer'] ?? null;
        $this->expectedBucketOwner = $input['ExpectedBucketOwner'] ?? null;
        $this->ifMatch = $input['IfMatch'] ?? null;
        $this->ifNoneMatch = $input['IfNoneMatch'] ?? null;
        $this->sseCustomerAlgorithm = $input['SSECustomerAlgorithm'] ?? null;
        $this->sseCustomerKey = $input['SSECustomerKey'] ?? null;
        $this->sseCustomerKeyMd5 = $input['SSECustomerKeyMD5'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Bucket?: string,
     *   Key?: string,
     *   MultipartUpload?: null|CompletedMultipartUpload|array,
     *   UploadId?: string,
     *   ChecksumCRC32?: null|string,
     *   ChecksumCRC32C?: null|string,
     *   ChecksumSHA1?: null|string,
     *   ChecksumSHA256?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   ExpectedBucketOwner?: null|string,
     *   IfMatch?: null|string,
     *   IfNoneMatch?: null|string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   '@region'?: string|null,
     * }|CompleteMultipartUploadRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->bucket;
    }

    public function getChecksumCrc32(): ?string
    {
        return $this->checksumCrc32;
    }

    public function getChecksumCrc32C(): ?string
    {
        return $this->checksumCrc32C;
    }

    public function getChecksumSha1(): ?string
    {
        return $this->checksumSha1;
    }

    public function getChecksumSha256(): ?string
    {
        return $this->checksumSha256;
    }

    public function getExpectedBucketOwner(): ?string
    {
        return $this->expectedBucketOwner;
    }

    public function getIfMatch(): ?string
    {
        return $this->ifMatch;
    }

    public function getIfNoneMatch(): ?string
    {
        return $this->ifNoneMatch;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getMultipartUpload(): ?CompletedMultipartUpload
    {
        return $this->multipartUpload;
    }

    /**
     * @return RequestPayer::*|null
     */
    public function getRequestPayer(): ?string
    {
        return $this->requestPayer;
    }

    public function getSseCustomerAlgorithm(): ?string
    {
        return $this->sseCustomerAlgorithm;
    }

    public function getSseCustomerKey(): ?string
    {
        return $this->sseCustomerKey;
    }

    public function getSseCustomerKeyMd5(): ?string
    {
        return $this->sseCustomerKeyMd5;
    }

    public function getUploadId(): ?string
    {
        return $this->uploadId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
        if (null !== $this->checksumCrc32) {
            $headers['x-amz-checksum-crc32'] = $this->checksumCrc32;
        }
        if (null !== $this->checksumCrc32C) {
            $headers['x-amz-checksum-crc32c'] = $this->checksumCrc32C;
        }
        if (null !== $this->checksumSha1) {
            $headers['x-amz-checksum-sha1'] = $this->checksumSha1;
        }
        if (null !== $this->checksumSha256) {
            $headers['x-amz-checksum-sha256'] = $this->checksumSha256;
        }
        if (null !== $this->requestPayer) {
            if (!RequestPayer::exists($this->requestPayer)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "RequestPayer" for "%s". The value "%s" is not a valid "RequestPayer".', __CLASS__, $this->requestPayer));
            }
            $headers['x-amz-request-payer'] = $this->requestPayer;
        }
        if (null !== $this->expectedBucketOwner) {
            $headers['x-amz-expected-bucket-owner'] = $this->expectedBucketOwner;
        }
        if (null !== $this->ifMatch) {
            $headers['If-Match'] = $this->ifMatch;
        }
        if (null !== $this->ifNoneMatch) {
            $headers['If-None-Match'] = $this->ifNoneMatch;
        }
        if (null !== $this->sseCustomerAlgorithm) {
            $headers['x-amz-server-side-encryption-customer-algorithm'] = $this->sseCustomerAlgorithm;
        }
        if (null !== $this->sseCustomerKey) {
            $headers['x-amz-server-side-encryption-customer-key'] = $this->sseCustomerKey;
        }
        if (null !== $this->sseCustomerKeyMd5) {
            $headers['x-amz-server-side-encryption-customer-key-MD5'] = $this->sseCustomerKeyMd5;
        }

        // Prepare query
        $query = [];
        if (null === $v = $this->uploadId) {
            throw new InvalidArgument(\sprintf('Missing parameter "UploadId" for "%s". The value cannot be null.', __CLASS__));
        }
        $query['uploadId'] = $v;

        // Prepare URI
        $uri = [];
        if (null === $v = $this->bucket) {
            throw new InvalidArgument(\sprintf('Missing parameter "Bucket" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Bucket'] = $v;
        if (null === $v = $this->key) {
            throw new InvalidArgument(\sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Key'] = $v;
        $uriString = '/' . rawurlencode($uri['Bucket']) . '/' . str_replace('%2F', '/', rawurlencode($uri['Key']));

        // Prepare Body

        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = false;
        $this->requestBody($document, $document);
        $body = $document->hasChildNodes() ? $document->saveXML() : '';

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setBucket(?string $value): self
    {
        $this->bucket = $value;

        return $this;
    }

    public function setChecksumCrc32(?string $value): self
    {
        $this->checksumCrc32 = $value;

        return $this;
    }

    public function setChecksumCrc32C(?string $value): self
    {
        $this->checksumCrc32C = $value;

        return $this;
    }

    public function setChecksumSha1(?string $value): self
    {
        $this->checksumSha1 = $value;

        return $this;
    }

    public function setChecksumSha256(?string $value): self
    {
        $this->checksumSha256 = $value;

        return $this;
    }

    public function setExpectedBucketOwner(?string $value): self
    {
        $this->expectedBucketOwner = $value;

        return $this;
    }

    public function setIfMatch(?string $value): self
    {
        $this->ifMatch = $value;

        return $this;
    }

    public function setIfNoneMatch(?string $value): self
    {
        $this->ifNoneMatch = $value;

        return $this;
    }

    public function setKey(?string $value): self
    {
        $this->key = $value;

        return $this;
    }

    public function setMultipartUpload(?CompletedMultipartUpload $value): self
    {
        $this->multipartUpload = $value;

        return $this;
    }

    /**
     * @param RequestPayer::*|null $value
     */
    public function setRequestPayer(?string $value): self
    {
        $this->requestPayer = $value;

        return $this;
    }

    public function setSseCustomerAlgorithm(?string $value): self
    {
        $this->sseCustomerAlgorithm = $value;

        return $this;
    }

    public function setSseCustomerKey(?string $value): self
    {
        $this->sseCustomerKey = $value;

        return $this;
    }

    public function setSseCustomerKeyMd5(?string $value): self
    {
        $this->sseCustomerKeyMd5 = $value;

        return $this;
    }

    public function setUploadId(?string $value): self
    {
        $this->uploadId = $value;

        return $this;
    }

    private function requestBody(\DOMNode $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->multipartUpload) {
            $node->appendChild($child = $document->createElement('CompleteMultipartUpload'));
            $child->setAttribute('xmlns', 'http://s3.amazonaws.com/doc/2006-03-01/');
            $v->requestBody($child, $document);
        }
    }
}
