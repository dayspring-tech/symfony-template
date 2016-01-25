Vagrant Template
============
This repo serves as a template for creating a LAMP Vagrant box.

Instructions
===
1. Edit the `Vagrantfile` to customize the `application_name`, `document_root`, and `database_info` (Line 80).
2. Copy `Vagrantfile.local.example` to `Vagrantfile.local` and fill in your GitHub OAuth token. See the instructions in the file for more details on generating a GitHub OAuth token.
3. If using Symfony, customize the path to Symfony by editing the `node[symfony][root]` of `chef.json` (Line 92). Then rename
`deploy/before_symlink-symfony.rb` to `deploy/before_symlink.rb`.
