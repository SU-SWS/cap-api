# CAP API Library
#### Version 0.0.0

This API library is responsible for the communication between your PHP project
and the API on cap.stanford.edu

## What is CAP?

CAP Network is a virtual workspace, originally created by the School of Medicine, to support collaboration among faculty, graduate students, postdocs and staff. In 2013, it was expanded in partnership with various Schools, Institutes, and administrative offices to create the Stanford Profiles website.

Combining a profile directory with a social networking backend, CAP makes it easy for you to work closely with colleagues and track the projects that matter most to you—all in a private, secure environment

* [profiles.stanford.edu](https://profiles.stanford.edu)
* [cap.stanford.edu](https://cap.stanford.edu/)

## Authentication

Before you get started you will need to have authentication credentials. To get authentication credentials, [file a HelpSU request](https://helpsu.stanford.edu/helpsu/3.0/auth/helpsu-form?pcat=CAPAPI&dtemplate=CAP-OAuth-Info) to Administrative Applications/CAP Stanford Profiles.

## Configuration

For detailed documentation on the configuration and usage of this module, please see the [Docs section](./docs/).

## Developer

[GitHub](https://github.com/SUSWS/stanford_capx) page.
Collaboration and bug reports are welcome. Please file bug reports on the github issues page. You are also welcome to suggest new functionality in the way of a pull request.

### Security
#### HTTPS
cap-api uses https for all API calls. Please follow this best practice as you develop with this module.
#### httpoxy mitigation:
In July 2016, the httpoxy security exploit was announced for PHP, including libraries such as Guzzle. cap-api installs were by default protected because of https usage (see above). In addition, **developers are encouraged to seek their own httpoxy mitigation steps at the server level**. Check with your hosting provider to ensure that your implementation is protected from httpoxy. See https://httpoxy.org for details.
