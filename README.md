# Simplechart Dev Site

This repo exists to help with local development for the [Simplechart][1] JS app and the [WordPress Simplechart][2] plugin.

## Changing JS versions

When the Simplechart Dev Mode plugin is activated, you'll find this option in your user settings.

![user settings](img/user-settings.png)

Note that the WordPress toolbar indicates which version of the JS source is active.

## Local JS app development

First get the local Node environment running:

```
$ git clone git@github.com:alleyinteractive/simplechart.git
$ cd simplechart
$ npm install
$ npm run watch
```
Then in your local WordPress site:

1. Go to `/wp-admin/profile.php`
1. Select `localhost:8080` under **Simplechart Dev Mode**
1. Go to `/wp-admin/post-new.php?post_type=simplechart`
1. The chart editor app should open from `http://localhost:8080/static/app.js`

## Local WordPress plugin development

To work on the WordPress plugin, you'll delete the version that came with this repository, and clone just the plugin from GitHub.

Because `plugins/wordpress-simplechart/.git` is ignored, you can make changes in the plugin directory and commit them to **both** `simplechart-dev-site` and `wordpress-simplechart` separately.

```
$ cd {the plugins/ dir of your simplechart-dev-site install}
$ rm -rf wordpress-simplechart
$ git clone git@github.com:alleyinteractive/wordpress-simplechart.git
$ cd wordpress-simplechart
# checkout branches, commit changes, etc
$ git push origin my-branch # pushes to alleyinteractive/wordpress-simplechart
$ cd ..
# checkout branches, commit changes, etc
$ git push origin my-branch # pushes to alleyinteractive/simplechart-dev-site
```

Notes:

1. `plugins/wordpress-simplechart` does _not_ need to initialized as a submodule
1. You _can_ do local JS app development and WordPress plugin development at the same time

## Automated deployments

The `master` branch of this repo is automatically deployed from GitHub to http://dev-simplechart.alleydev.com/, **except for**:

* `plugins/wordpress-simplechart` (`develop` branch of [alleyinteractive/wordpress-simplechart][2])
* `plugins/simplechart-dev-mode/js` (`develop` branch of [alleyinteractive/simplechart][2])

[1]: https://github.com/alleyinteractive/simplechart
[2]: https://github.com/alleyinteractive/wordpress-simplechart
[3]: https://github.com/alleyinteractive/simplechart-dev-site
