# CF7 Email Domain Filter

An add-on plugin for Contact Form 7 which allows you to exclude email domains submitted through a form.

## Description

The purpose of this Contact Form 7 add-on plugin is to minimize form submissions from questionable email addresses. Spammers and spambots often use free email services when completing forms. If they can surpass security measures like honey pot or CAPTCHA, they can successfully complete a form submission. This will fill your mailbox with junk and useless messages with the intent of wasting your time or getting you to click something potentially malicious.

This WordPress plugin allows you to define a list of blocked email domains, select which forms you want validated and display a warning message for the User to input a different email address.

## Getting Started

### Installing

1. Download the plugin
2. Upload the cf7-email-filter folder to your site's wp-content/plugins directory
3. Activate the plugin in the WordPress Admin Panel
4. In the Admin Panel, go to Contact->Email Filter or Settings->CF7 Email Filter
5. Verify/update the **List of Blocked Emails**, add a custom **Warning Message**, and choose which Contact Form 7 Forms you want the validation activated

## For Developers

### Running PHPUnit Tests

```shell
# run tests
./php_tests.sh basic

# run testdox
./php_tests.sh testdox

# run a specific test
./php_tests.sh file BuildListTest
```

### Running E2E Tests with Cypress

```shell
npm run cypress
```

## Version History

* 0.1
  * Initial Release

## License

This project is licensed under the GPLv2 License. For more information, please read [GNU General Public License, version 2](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)

## Acknowledgments

Inspiration, code snippets, etc.

* [WordPress Plugin Development Cookbook - Third Edition](https://www.packtpub.com/product/wordpress-plugin-development-cookbook-third-edition/9781801810777)
* [awesome-readme](https://github.com/matiassingers/awesome-readme)
* [Why explode by space not work in some space string](https://stackoverflow.com/questions/59045002/why-explode-by-space-not-work-in-some-space-string)
* [How to get List for Contact Form 7](https://stackoverflow.com/questions/38518093/how-to-get-list-for-contact-form-7)
* [Contact Form 7 custom validation for specific form id](https://stackoverflow.com/questions/38383795/contact-form-7-custom-validation-for-specific-form-id)
