# AWS CloudFront Private Content for Craft CMS

Create CloudFront signed URLs on the fly to protect your assets.

## Overview

This plugin utilizes the AWS Cloudfront API to generate signed URLs. For an overview on serving private content via AWS CloudFront, read the [how-to guide on Amazon](http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/PrivateContent.html).

You can limit access to static assets via,

* setting an expiration date on the URL
* locking the URL down to IP addresses of the end-user

## Requirements

* PHP 5.5+
* an AWS CloudFront distribution, configured to serve private content
* CloudFront Key Pair private key file ([AWS docs](http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/private-content-trusted-signers.html))

## Installation

To install, follow these steps:

1) Install with Composer (recommended)

```
composer require benjamin-smith/craft-awscloudfront-private-content
```

-OR- download & unzip the file and place the `awscloudfront` directory into your `craft/plugins` directory

-OR- do a `git clone https://github.com/benjamin-smith/craft-awscloudfront-private-content.git` directly into your `craft/plugins` folder.  You can then update it with `git pull`

2) Place your CloudFront Key Pair private key in `craft/storage/awscloudfront/cloudfront.pem`.

3) Install plugin in the Craft Control Panel under Settings > Plugins

## Configuring

Create a config file in `craft/config/awscloudfront.php` with the following settings:

```php
<?php
return [
  'awsRegion'         => 'us-east-1',
  'hostUrl'           => 'https://example-distribution.cloudfront.net',
  'keyPairId'         => 'xxx',
];
```

Then create a sample "policy" in the plugin settings menu. You can configure the expiration time and whether or not to restrict access to URLs based on IP address. You can have multiple policies, and choose which to use with each URL that is generated.

## Using

This plugin creates a signed URL from a non-signed CloudFront URL. For example, if your resource is:

`https://example-distribution.cloudfront.net/path/to/file.pdf`

Your template tag would be:

```
{{ craft.awscloudfront.getPrivateUrl('path/to/file.pdf', 'yourPolicyHandle') }}
```

Or, you can generate a signed URL from a custom plugin:

```php
craft()->awsCloudfront_privateResource->getPrivateUrl('path/to/content', 'yourPolicyHandle');
```

## Roadmap

* tighter integration with Craft Assets
* ability to configure multiple CloudFront distributions
* ability to use signed cookies to make content private
