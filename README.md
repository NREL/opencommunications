#1 Introduction
The master branch of this profile is no longer a sub-profile of the <a href="https://github.com/acquia/lightning">Lightning profile</a>.

The master branch is being used to build a simplified Drupal 9 profile for our own purposes, which will not implement the same out-of-the-box functionality currently in the Drupal 8 profile, and that does not have the dependency on Lightning.

The stable Drupal 8 versions of this profile are still available on branches 4.1.3, 4.1.5 and 4.1.9, but will reach end-of-life in November 2021 when <a href="https://www.acquia.com/blog/acquia-lightning-eol-2021-acquia-cms-future">Lightning reaches end-of-life</a> at the same time as Drupal 8 reaches end-of-life.

#2 Description of Drupal 9 profile

This profile provides composer.json / composer.lock starter files (in assets/scaffold/files/composer/) that can be copied
into a new project folder to scaffold that project. Running composer install against those composer files does the following:

- scaffolds the site under docroot/
- symlink web/ to docroot/
- create a drupal_config/config/ folder if one doesn't exist and copy the starter config from this profile into that folder
    - the modules that are enabled and configured by this profile are:
        - admin_toolbar
        - admin_toolbar_links_access_filter
        - admin_toolbar_tools
        - advagg
        - advagg_css_minify
        - advagg_js_minify
        - advagg_mod
        - advagg_validator
        - big_pipe
        - blazy
        - blazy_ui
        - block
        - bootstrap_layouts
        - breakpoint
        - captcha
        - ckeditor
        - config
        - contextual
        - crop
        - ctools
        - datetime
        - dblog
        - dynamic_page_cache
        - editor
        - embed
        - entity_browser
        - entity_embed
        - entity_reference_revisions
        - field
        - field_group
        - field_ui
        - file
        - file_mdm
        - filter
        - google_tag
        - help
        - honeypot
        - image
        - image_widget_crop
        - imagemagick
        - layout_builder
        - layout_discovery
        - link
        - media
        - media_library
        - memcache
        - memcache_admin
        - menu_link_content
        - menu_ui
        - metatag
        - node
        - options
        - page_cache
        - password_policy
        - password_policy_character_types
        - password_policy_characters
        - password_policy_history
        - password_policy_length
        - password_policy_username
        - path
        - path_alias
        - rdf
        - recaptcha
        - seckit
        - security_review
        - smtp
        - sophron
        - system
        - taxonomy
        - text
        - token
        - toolbar
        - update
        - user
        - video_embed_field
        - video_embed_media
        - views_ui
        - webform
        - webform_ui
        - pathauto
        - views
        - paragraphs
        - opencommunications
- invokes the scaffolding for opencommunications which does the following:
    - copies the starter .gitignore, settings.php files and ses_mailer services yaml from assets/scaffold/files to their
      respective project locations.
    - The Blazy module (https://www.drupal.org/project/blazy) does not specify a dependency on the Blazy library
      (https://github.com/dinbror/blazy), so the Blazy library is included as a scaffolding asset. If the Blazy library
      needs to be updated download the latest version to assets/scaffold/files/blazy
    - applies known outstanding patches to core and the included contrib modules

#3 Usage

##3.1 Create and install a new site from the profile

- mkdir my_new_project<br>
- cd my_new_project<br>
- wget https://raw.githubusercontent.com/NREL/opencommunications/master/assets/scaffold/files/composer/composer.json<br>
- wget https://raw.githubusercontent.com/NREL/opencommunications/master/assets/scaffold/files/composer/composer.lock<br>
- edit composer.json - global replace new_project with my_new_project, and site_name with my_new_site_name<br>
- composer install<br>
- drush si opencommunications --existing-config --account-name=account-name --account-mail=account-mail --account-pass=account-pass --db-url=db-driver://db-su:db-su-pw@db-host:db-port/db-name -y
- drush cset system.site name "my_new_site_name" -y<br>
- drush cset system.site mail no_reply@example.com -y<br>
- Move database connection details that get added at the end of the existing setting.php into settings.local.php

##3.2 Optional

##3.2.1 reCaptcha
- Create a recaptcha account for the site<br>
- set the reCaptcha site and secret keys:<br>
  - drush cset recaptcha.settings site_key new_site_key -y<br>
  - drush cset recaptcha.settings secret_key new_secret_key -y<br>
- drush cset captcha.settings enabled_default 1 -y<br>

##3.2.2 Google Tag Manager
- set the id on the 'default' GTM container
  - drush cset google_tag.container.default container_id GTM-xxxxxx -y<br>

##4 Update an existing site that uses the profile

- composer update nrel/opencommunications

#5 Updating this profile

##5.1 Update a package

- composer update vendor/package
- add, commit and push the changes

##5.2 Update the template composer.lock

- create a dummy project
  - mkdir dummy_project<br>
  - cd my_new_project<br>
  - wget https://raw.githubusercontent.com/NREL/opencommunications/master/assets/scaffold/files/composer/composer.json<br>
- update the composer.lock
  - composer install
- copy the composer.lock into NREL/opencommunications/master/assets/scaffold/files/composer/composer.lock
- add, commit and push the changes to composer.lock
