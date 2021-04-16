The master branch of this profile is no longer a sub-profile of the <a href="https://github.com/acquia/lightning">Lightning profile</a>.

The master branch is being used to build a simplified Drupal 9 profile for our own purposes, which will not implement the same out-of-the-box functionality currently in the profile, and that does not have the dependency on Lightning. **The code on the master branch is not stable and should not be used**.

The stable Drupal 8 versions of this profile are still available on branches 4.1.3, 4.1.5 and 4.1.9, but will reach end-of-life in November 2021 when <a href="https://www.acquia.com/blog/acquia-lightning-eol-2021-acquia-cms-future">Lightning reaches end-of-life</a> at the same time as Drupal 8 reaches end-of-life.

#2 Usage

> mkdir my_new_project<br>
> cd my_new_project<br>
> wget https://raw.githubusercontent.com/NREL/opencommunications/master/assets/scaffold/files/composer.json<br>
> edit composer.json - global replace new_project with my_new_project, and site_name with my_new_site_name<br>
> composer install<br>
> drush si opencommunications --existing-config --account-name=admin_name --account-mail=admin_mail --account-pass=admin_password —site-name="my_new_site_name"<br>
> Move database connection details that get added at the end of the existing setting.php into settings.local.php



