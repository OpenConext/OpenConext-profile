# Next release

# 4.1.4
* Update saml2 library.
* 
# 4.1.3
* Change some terminology to new OpenConext defaults.
* Update for libraries.

# 4.1.2
* Deal with invite roles not having a description field.
* Updates for various libraries.

# 4.1.1
* Ensure the Invite roles overview logo is displayed nice #297

# 4.1.0
* Add optional OpenConext-Invite roles overview #294
* Use support.surfconext.nl to link documentation #295 (thanks @FlorisFokkinga)
* Security updates

# 4.0.1  
* Centralized services in cerntal services.yaml
* Replaced guzzle with symfony native
* Moved some extension config to yaml values

# 4.0.0
* Upgraded dependencies to their latest compatible version
* Fixed all direct deprececations (SF 7.0 ready)
* Routes are migrated to attributes
* Using new security authentication system
* legacy moved to openconext
* removed .env and using all parameters
* simplified kernel (using MicroKernelTrait)
* Using property promotion as much as possible
* strict typing everywhere
* typed variables where detected by rector or manual changes if detected

# 3.1.8

**Fixes**
* Add missing oid for SAML eduPersonAssurance attribute.
* Update dependencies for security fixes

# 3.1.7

**Fixes**
* Fix translation overrides.

# 3.1.6

**Fixes**
* Fix translation in connection disconnect dialog
* Fix translation overrides for a subset of keys of a nested array
* Update dependencies for security fixes

# 3.1.5

**Improvements**
* Add translation for SAML subjectId attribute.

# 3.1.4

**Fixes**
* Add translation for Organizational Unit attribute
* Update dependencies for security fixes

# 3.1.3

**Fixes**
* Update dependencies for security fixes

# 3.1.2

**Fixes**
* Fix Symfony 4.4 compatibility issue

# 3.1.1

**Features**
* Profile SP metadata now includes signing certificate

**Improvements**
* Make the cache path var/cache/prod again instead of var/prod/cache
* Show aggregated attributes even if SP has no IdP-attributes
* Translate two more attributes
* Fix translation of information-request email

# 3.1.0

**Features**
* Show attribute aggregation attributes on the my-services page #235
* Add the option to store sessions in the database #227

**Improvements**
* Log which user authenticated to the application. #229
* Remove SURFisms from translation files #233
* Prevent unsafe-inline CSP errors on SVG images #234
* Expose info and health on /internal by updating the Monitor bundle #236
* Replace deprecated Swiftmailer with Symfony mailer #241
* Install Mailcatcher and document the change #239
* Show the organization name on the my-services page #238

**Maintenance**
* Install periodic security upgrades #226

**Bugfix**
* Enable test-integration runs on develop #240

# 3.0.4

* Create release artifacts when project is tagged #225
* Fix IdP logo display in My Services overview #224
* Downgrade 'normal operation' log messages to info #223

# 3.0.3
**Bugfixes**
- Ensure something incorrect disclosure is shown.
- Ensure support icons have the right colour
- Use correct institution data

**Features**
- Translations are now overrideable.  This is a BC breaking change compared to pre v3 releases.

# 3.0.2
**Bugfixes and chores**
 - Company rebranding
 - Add ansi-regex resolution adressing GitHub advisory #209
 - Update Swiftmailer configuration #218
 
# 3.0.1
**Bugfixes**
- Ensure givenName is not required for using Profile #205
- Repair infomation request and attribute support pages #208
- Support-mail and EULA trouble, where faulty data was used to show the links #211
- Pinch missing translation from my services page #213
- Docker: OpenConext prep script moved to /usr/local/sbin #215
- Show delete description when feature flag is ON #212

**Feature**
- NL language improvements #490407db
- Order the consent list alphabetically #216
- Show custom error pages #210
- Add entity Id to my services #214

**Chores**
- Remove global_view_parameters.yaml from VCS #207
- Move makeRelease to bin folder #206
- Temporarily disable AA aatributes in my services #217

# 3.0.0

**Changes**
- Redesign every screen for a better user experience, accessibility and to improve the look/feel
- Allow users to remove consent per service
- Add cypress tests for accessibility
- Add docker for development / deploys
- Improve documentation

**Security**
- Upgrade symfony version to 4.4 so we have LTS again
- Upgrade several packages (over 777 vulnerabilities fixed)
- Add the secure flag to the lang cookie

# 2.0.3

This is a security release that will harden the application against CVE 2019-3465


# 2.0.2

**Changes**

 * Optimize ORCiD button placement for all devices #136
 * Add ECK ID saml attribute translation #133
 * Fix the data retention translation #134

# 2.0.1
**Changes**

* Add /build to asset paths #131
* Bump Stepup SAML bundle to 4.1.5 #132

# 2.0.0

Version 2.0 drops PHP 5.6 support. And more important Symfony was updated to version 3.4

The major changes of this version:

**Improvements**
* Upgrade to Symfony 3.4 and fix deprecation issues while at it #126
* Added Portuguese translations #121 
* Update ORCiD ID styling on the my connections page #128
* Install and configure Webpack Encore #130

**Maintenance**
* Bump Stepup SAML bundle to version 4.1.4 #125
* Install PHP 7.2 and update Composer dependencies #124
* Address security issues #127

# 1.2.2

Further removes the SURFconextId usages in the project. The AA Api client still used the attribute, causing issues on the My connections page. Thanks @domgon for raising the issue!

**Bugfix**
* Stop using SurfConextId in the AA client #118 
* Add Portuguese language support #119 (Thanks @domgon!) #120

**Maintenance**
 * Install Symfony and Twig security updates #119
 * Removed RMT from the project
 * Updated documentation links
 * Upgraded Security Checker to version 5

# 1.2.1

In order to be compatible with EngineBlock 5.9, Profile needed to stop using the SURFconextId. As Engine no longer releases it.

**Bugfix**
* Stop using SURFconextId as user identifier #114 

**Maintenance**
* Security updates #113

# Old releases

## VERSION 1  OPENCONEXT PROFILE
### Version 1.2 - Provide User Lifecycle support
#### 22/01.2019 15:38  1.2.0  initial release

### Version 1.1 - Various improvements to the 'My services' and 'My connections' pages
#### 24/05/2018 10:00  1.1.1  Allow service providers without NameIDFormat
#### 07/03/2018 10:00  1.1.0  SSO improvements, allow IDPs without contacts and better error templates

### Version 1.0 - Various improvements to the 'My services' and 'My connections' pages
#### 06/03/2018 12:23  1.0.3  Security patches for https://simplesamlphp.org/security/201803-01
#### 05/02/2018 12:23  1.0.2  Security patches for simplesamlphp/saml2 and paragonie/random_compat
#### 05/02/2018 12:23  1.0.1  SAML2 library upgrade and EB 5.5 compatibility changes
#### 15/01/2018 10:00  1.0.0  initial release


## VERSION 0  FIRST PRE-RELEASE OF PROFILE APPLICATION EXTRACTED FROM ENGINEBLOCK

### Version 0.6 - Changed consumption of EB Consent API due to changes in EB 5.2
#### 14/02/2017 13:46  0.6.0  initial release

### Version 0.5 - Apply ARP to attributes in My Services, requires EB >= 5.1
#### 03/11/2016 09:53  0.5.1  This patch fixes issues when applying the ARP to attributes without a definition known to Profile.
#### 01/11/2016 13:16  0.5.0  initial release

### Version 0.4 - Update Profile with improvements
#### 24/10/2016 13:11  0.4.0  initial release

### Version 0.3 - Made locale cookie more configurable, fixed dealing with mattribute multiplicity, corrected development ip for engineblock
#### 14/06/2016 10:58  0.3.0  initial release

### Version 0.2 - Added script to aid with deployment of tar-balls
#### 03/06/2016 10:06  0.2.0  initial release

### Version 0.1 - First pre-release of Profile application extracted from EngineBlock
#### 31/12/2015 13:51  0.1.0  initial release
