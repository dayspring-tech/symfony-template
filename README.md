# Symfony Template

This repo serves as a template for creating a "Dayspring Standard" Symfony 3.4 project.
Download this code to start a new project.

### Included libraries/packages
- Symfony 3.4.x
- Bootstrap 3 (sass)
- Propel/PropelBundle
- Angular 6.x
- BraincraftedBootstrapBundle (for Symfony form styling)
- Flysystem/FlysystemBundle
- JWT (lcobucci/jwt)
- Dayspring Login Bundle

#### Other Tools
- Vagrant box: bento/amazonlinux-2 (PHP 7.4, Node 10.x, MySQL 5.7)

### Instructions
1. Edit the `Vagrantfile` to customize the `application_name`, `document_root`, and
`database_info` (Line 80).
2. Copy `Vagrantfile.local.example` to `Vagrantfile.local` and fill in your GitHub
OAuth token. See the instructions in the file for more details on generating a GitHub OAuth token.
3. Run `vagrant up`
4. Browse to [http://localhost:8080/app_dev.php/_demo/](http://localhost:8080/app_dev.php/_demo/) to view the bootstrap theme
test file.

### Angular2 / Bootstrap / Webpack
This template project includes the angular2-starter from [https://github.com/mdenson-dayspring/angular2-starter](https://github.com/mdenson-dayspring/angular2-starter). (As of 2017-03-09, [8ca8e05](https://github.com/mdenson-dayspring/angular2-starter/tree/8ca8e05dd30e66d0e4319a4fe5e53e3e8ddad108))

Webpack handles angular2 as well as Bootstrap. The Symfony dev environment is expecting webpack-dev-server to be running at http://localhost:3000/ and will pull assets for the "webpack" asset group from there. 

#### To start (run commands inside `angular` directory):
1. Install Node/NPM. (Node version 6.7.0+)
2. Install Yarn `npm install -g yarn`
3. Install dependencies `yarn`
4. Run `yarn start` to start the dev server
5. Browse to [http://localhost:8080/app_dev.php/_demo/angular](http://localhost:8080/app_dev.php/_demo/angular) to view the sample angular2 app

#### To build:
1. Run `yarn build`
2. Artifacts from the build will be found in `symfony/web/webpack/prod`. Symfony's prod environment is configured to look there for assets for the "webpack" asset group.


#### Bootstrap
bootstrap-sass is included in this template project. Webpack will compile styles to `app.css`. Add or override styles in `angular/src/sass/styles.scss`


# Deployment to AWS

This template project is designed to deploy to AWS via Opsworks. Read [DEPLOY_AWS.md](DEPLOY_AWS.md) for details


# Third party frameworks and libraries

This is a list of the third party frameworks and libraries that the development team
will monitor via development mailing lists for vulnerabilities.  Vulnerabilities which
are found will be addressed in a Pull Request to indicate that the version was changed
to address the vulnerability.

| Framework/ Library | Version  | License
| ------------------ | -------- | -------
| Framework 1        | 2.3.x    | MIT
| Library 1          | 1.7.1    | MIT
| ...                | x.x.x    | ...

[Roll out instructions](ROLLOUT.md "Instructions for rolling to stage and production.")


# Prepare your code for Submission to Project

Whenever you feel that your code is ready for submission, follow the following steps.

### Step 1. Sync feature branch current code tree

In most cases you will have created a feature branch starting from the develop branch.
In other cases you may have created a hotfix from master or a feature branch from a
release branch.

It is probably simplest to go into bitbucket and Sync the branch you have been working on.
If there are conflicts you will need to merge the correct branch into your working branch
on you development machine and fix the conflicts.

### Step 2. Make a Pull Request

You are now ready to create a pull request in bitbucket on the project's repository.

The pull request description must include the following checklist at the top to ensure
that contributions may be reviewed without needless feedback loops and that your
contributions can be included into the project as quickly as possible.

```md
| Q                | A
| ---------------- | ---
| Bug fix?         | [yes|no]
| New feature?     | [yes|no]
| Tests pass?      | [yes|no]
| New form inputs? | [yes|no]
| Other top 10?    | [yes|no] if yes enter OWASP numbers
| Fixed cards      | [url of Trello cards]

### Added libraries

| Name            | Version | Licence    | Added to README
| --------------- | ------- | ---------- | ---------------
| [name]          | [x.x.x] | [lic]      | [yes|no]
```

Here is a simple example for a pull request that adds a field to a form as a new
feature.

```md
| Q                | A
| ---------------- | ---
| Bug fix?         | no
| New feature?     | yes
| Tests pass?      | yes
| Form inputs?     | yes
| Other top 10?    | none
| Fixed cards      | https://trello.com/c/Wysdfsdfs, https://trello.com/c/Wymrdfsfs

### Added libraries

| Name            | Version | Licence    | Added to README
| --------------- | ------- | ---------- | ---------------
| none | | |
```

The whole checklist must be included (do **not** remove lines that you think are not
relevant).

### Step 3: Pick the Reviewer(s)

Pick the Lead Developer or Project Manager to add as a reviewer to the pull request
and Save.
