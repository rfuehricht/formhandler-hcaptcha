# EXT:formhandler_hcaptcha

Adds error check hcaptcha to Formhandler.

Requires `dreistromland/typo3-hcaptcha`.

## Usage

Register and get your Site Key and Private Key from https://www.hcaptcha.com/

Enter your data in TypoScript or via `ENV` variables. See [documentation of EXT:hcaptcha](https://extensions.typo3.org/extension/hcaptcha) for details.

```text
plugin.tx_hcaptcha {
  settings {
    publicKey = <your-site-key>
    privateKey = <your-private-key>
  }
}
```

Add captcha field to your form:

```html
<html
    data-namespace-typo3-fluid="true"
    xmlns:f="http://typo3.org/ns/fluid/ViewHelpers"
    xmlns:fh="http://typo3.org/ns/Rfuehricht/Formhandler/ViewHelpers"
    xmlns:hcaptcha="http://typo3.org/ns/Waldhacker/Hcaptcha/ViewHelpers"
>

<hcaptcha:forms.hcaptcha />
<fh:errorMessages as="message" error="hcaptcha" field="hcaptcha">
    <div class="error">{message}</div>
</fh:errorMessages>

</html>

```

Configure error check in TypoScript:

```text

validators {
  1 {
    config {
      fieldConf {
        hcaptcha.errorCheck.1 = Rfuehricht\FormhandlerHcaptcha\ErrorCheck\Hcaptcha
      }
    }
  }
}
```

