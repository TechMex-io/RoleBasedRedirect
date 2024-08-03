# RoleBasedRedirect
A module for Processwire CMS to redirect users based on their role upon login.

## Install
```
$ cd site/modules
$ mkdir RoleBasedRedirect
$ cd RoleBasedRedirect
$ git clone git@github.com:TechMex-io/RoleBasedRedirect.git .
```
Install the module via the `Modules` section of the Processwire admin.

## Configuration
In the module's settings, add the key value pairs of `userrole=/URL/`, for example:
```
admin=/clients/
```
with this, any user with a role of `admin` will be redirected to `/clients/`.
