# Upgrade

Any major updates which include breaking changes will be documented here.

If you are upgrading a major version, you should always refer to this document to ensure you make all necessary changes.

When upgrading, you should upgrade one version at a time.

## [v3.0]

### Filters

If you have created any custom filters, you will need to convert the options() function to return a \FormSchema\Schema\Form class.

## [v2.0]

### Database User

If you rely on the Forename, Surname, Email or Student ID columns in the database user model, these have now been removed. You can access the same data
by getting the data user from the control model.

The Database \BristolSU\Support\User\UserRepository::getWhereIdentity function has been removed and should be replaced with getWhereEmail or getFromControlID.
