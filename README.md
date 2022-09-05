# Magento 2 Gmail Markup extenstion

## 1. Documentation

- [Contribute on Github](https://github.com/marcinmaterzok/magento2-email-gmail-markup)
- [Releases](https://github.com/marcinmaterzok/magento2-email-gmail-markup/releases)
- [Google Registration Guidelines](https://developers.google.com/gmail/markup/registering-with-google)
- [What is Gmail Markup?](https://developers.google.com/gmail/markup)
  

## 2. How to install

### Install via composer (recommend)
**1. Run the following command in Magento 2 root folder:**
```
composer require mtrzk/magento2-gmail-markup
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```
**2. Configure module in Magneto 2 Admin panel**

**3. IMPORTANT! You need to manually add variable in you email templates (in header in head tag).**
```
{{var gmailMarkup|raw}}
```

## 3. How to register in Google

1. If you want to use this module you need to check "Email Sender Quality guidelines" section on
https://developers.google.com/gmail/markup/registering-with-google
2. Enable module, and check order and shipment email via https://www.mail-tester.com/ 
3. Register on this form: 
https://docs.google.com/forms/d/e/1FAIpQLSfT5F1VJXtBjGw2mLxY2aX557ctPTsCrJpURiKJjYeVrugHBQ/viewform?pli=1
4. If you also want to use ViewOrder and TrackAction action you need to check "Actions / Schema Guidelines" section


## 4. CHANGELOG
Version 1.0.0

```
- First commit
- Added support for Order emails
- Added support for Shipment emails
- Advanced configuration per store
```
