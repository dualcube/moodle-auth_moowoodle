MooWoodle Connect for Moodle
==============================================

# Table of Contents

- [Description](#description)
- [Features](#features)
- [Password Synchronisation (Security Note)](#password-synchronisation-security-note)
- [Plugin Version](#plugin-version)
- [Required version of Moodle](#required-version-of-moodle)
- [Free Software](#free-software)
- [Support](#support)
- [Installation](#installation)
- [Uninstallation](#uninstallation)
- [Files Information](#files-information)
- [History](#history)
- [Author](#author)
- [Provided by](#provided-by)

# Description

MooWoodle Connect is a Moodle authentication plugin that provides Single Sign-On and user
synchronization between a Moodle site and the MooWoodle WordPress plugin.

It lets a WordPress site running MooWoodle:

* Log a user into Moodle via a signed, time-limited SSO link, without a separate Moodle login.
* Pull batches of Moodle user data (`auth_moowoodle_get_users`) and push user create/update
  requests into Moodle (`auth_moowoodle_user_sync`) over Moodle's web services API.

1. <a href="https://wordpress.org/plugins/moowoodle/">MooWoodle - WordPress Add On</a>
2. <a href="https://github.com/dualcube/moodle-auth_moowoodle">MooWoodle Connect - Moodle Add On</a>

1. <a href="https://dualcube.com/product/moowoodle-pro/">MooWoodle Pro</a>

So to enhance its functionality and achieve a fully automated WooCommerce solution for selling
your Moodle courses, you may consider choosing MooWoodle Pro to be installed on your
WordPress site.

Please note: the MooWoodle WordPress plugin (https://downloads.wordpress.org/plugin/moowoodle.zip)
is mandatory for the setup and has to be installed on your WordPress site for WordPress -
Moodle integration. Refer to this documentation for setup:
https://dualcube.com/docs/moowoodle-set-up-guide/

[(Back to top)](#table-of-contents)

# Features

* Single sign-on from WordPress into Moodle using a shared secret key and a short-lived,
  signed login link.
* Two-way user synchronization: WordPress can create or update Moodle users (username,
  email, name, and — when supplied — password), matched by email.
* A batched user-export endpoint, restricted to a chosen set of roles, for WordPress to pull
  existing Moodle users.
* A guided setup wizard that checks prerequisites, creates the Moodle web service and access
  token, and walks through connecting to the WordPress site.
* Fine-grained control over which additional web service functions (beyond the two this
  plugin requires) are granted to the WordPress integration user.
* A reminder banner on Site administration pages, shown to anyone with the
  `moodle/site:config` capability, until the setup wizard has been completed.

[(Back to top)](#table-of-contents)

# Password Synchronisation (Security Note)

Prefer the SSO login link for signing users in - it never needs a Moodle password to leave
this site. Password synchronisation exists only for sites that genuinely need both systems to
share the same credentials, and is **off by default**.

If you turn on "Synchronise passwords" (`auth_moowoodle/syncpasswords`) in the plugin
settings:

* An encrypted copy of the Moodle password hash is sent to the WordPress site whenever an
  account is created or its password changes, so it can keep the same login working there.
* This only ever happens for accounts whose authentication method is "MooWoodle Connect" -
  never for accounts using any other Moodle auth method (manual, LDAP, OAuth 2, etc.).
* The hash is encrypted with the shared SSO secret key before it leaves Moodle, and the key
  itself is never included in that same request - the request is authenticated with a
  signature instead, so intercepting it does not also hand over the means to decrypt it.

Password hashes are sensitive even when encrypted. Only enable this setting if the WordPress
integration truly needs it, keep the shared secret key confidential, and make sure the
connection between the two sites is trustworthy (HTTPS, and a WordPress site you control).

[(Back to top)](#table-of-contents)

# Plugin Version

v1.1.0 (Build: 2026083102) - Latest

[(Back to top)](#table-of-contents)

# Required version of Moodle

This plugin requires Moodle 5.0 and above, and is tested and supported through the Moodle 5.3
series (including the 5.3dev branch), per the `$plugin->requires` and `$plugin->supported`
declarations in `version.php`.

[(Back to top)](#table-of-contents)

# Free Software

MooWoodle Connect is free software under the terms of the GNU General Public License v3, or
(at your option) any later version — see the license header in any plugin file, or
http://www.gnu.org/copyleft/gpl.html.

If you are unsure about anything, the FAQ at http://www.gnu.org/licenses/gpl-faq.html is a
good place to look.

[(Back to top)](#table-of-contents)

# Support

For issues or questions about this plugin, please use the GitHub issue tracker at
https://github.com/dualcube/moodle-auth_moowoodle.

[(Back to top)](#table-of-contents)

# Installation

= Minimum Requirements =
* Moodle 5.0 or higher (see [Required version of Moodle](#required-version-of-moodle))
* The MooWoodle plugin installed and active on the WordPress site you want to connect to

= Moodle Plugin Automatic Installation =
* Go to Site administration > Plugins > Install plugins.
* Upload the plugin zip file.
* Click "Install plugin from the ZIP file".

= Moodle Plugin Manual Installation =
* Unzip the plugin and upload the resulting folder as `auth/moowoodle` in your Moodle
  installation, using the FTP application of your choice.
* Visit Site administration > Notifications to complete the install.

= Moodle Configuration =
Right after installation finishes, the next page an admin loads redirects straight into the
setup wizard at Site administration > Plugins > Authentication > MooWoodle Connect > Setup
wizard. Until the wizard is completed, a reminder also appears on Site administration pages
for anyone with the `moodle/site:config` capability.

The wizard walks through:

1. **General** — checking that web services, the REST protocol, and (recommended) extended
   username characters are enabled.
2. **Web Service** — creating (or reusing) the Moodle external service and access token that
   the MooWoodle WordPress plugin authenticates with, and choosing which additional web
   service functions to grant it.
3. **WordPress Site** — entering the WordPress site URL and the shared SSO secret key.
4. **Summary** — the Moodle site URL and access token to paste into the MooWoodle WordPress
   plugin settings.

The same fields can also be edited directly from the plugin's settings page afterwards.

[(Back to top)](#table-of-contents)

# Uninstallation

Go to Site administration > Plugins > Plugin overview, find MooWoodle Connect under
Authentication plugins, and click Uninstall. Then remove the `auth/moowoodle` folder from
your Moodle installation.

[(Back to top)](#table-of-contents)

# Files Information

Languages
---------
`lang/en` contains the language strings for this plugin.

Classes
-------
`classes/local` contains the setup wizard and settings-handling logic; `classes/settings`
contains the Moodle form definitions used by the wizard and settings page; `classes/event`
contains the observer for real-time user sync.

[(Back to top)](#table-of-contents)

# History

See the commit history at https://github.com/dualcube/moodle-auth_moowoodle/commits.

[(Back to top)](#table-of-contents)

# Author

DualCube

[(Back to top)](#table-of-contents)

# Provided by

DualCube (https://dualcube.com)

[(Back to top)](#table-of-contents)
