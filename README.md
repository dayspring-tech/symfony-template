# Symfony Template

This repo serves as a template for creating a "Dayspring Standard" Symfony 2.8 project.
Fork this repo to start a new project.

### Included libraries/packages
- Symfony 2.8.x
- Bootstrap 3
- Propel/PropelBundle
- Grunt
- BraincraftedBootstrapBundle (for Symfony form styling)
- Flysystem/FlysystemBundle
- JWT (lcobucci/jwt)
- Dayspring Login Bundle

#### Other Tools
- Vagrant box: dayspring-tech/dayspring-centos6-lamp-js (PHP 5.6, Node 4.2, MySQL 5.6)

### Instructions
1. Edit the `Vagrantfile` to customize the `application_name`, `document_root`, and
`database_info` (Line 80).
2. Copy `Vagrantfile.local.example` to `Vagrantfile.local` and fill in your GitHub
OAuth token. See the instructions in the file for more details on generating a GitHub OAuth token.
3. Run `vagrant up`
4. Browse to [http://localhost:8080/app_dev.php/_demo/] to view the bootstrap theme
test file.

### Notes
- Bootstrap LESS files are located in `/less`. Edit these to change styles.
- Run `npm run grunt-build` to recompile LESS to CSS


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

## Step 1. Sync feature branch current code tree

In most cases you will have created a feature branch starting from the develop branch.
In other cases you may have created a hotfix from master or a feature branch from a
release branch.

It is probably simplest to go into bitbucket and Sync the branch you have been working on.
If there are conflicts you will need to merge the correct branch into your working branch
on you development machine and fix the conflicts.

## Step 2. Make a Pull Request

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
| [name]          | [x.x.x] | [lic]      | {yes|no]
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

## Step 3: Pick the Reviewer(s)

Pick the Lead Developer or Project Manager to add as a reviewer to the pull request
and Save.
