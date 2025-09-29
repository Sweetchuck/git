# Git-related helper classes

[![CI](https://github.com/Sweetchuck/git/actions/workflows/ci.yml/badge.svg?branch=1.x)](https://github.com/Sweetchuck/git/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/Sweetchuck/git/graph/badge.svg?token=PRSN0MINBH)](https://codecov.io/gh/Sweetchuck/git)


## Commands

**Why use this package instead of \exec()?**

Using these commands instead of directly calling `\exec()` offers several advantages:

1. **Structured data handling** \
   Results are parsed into PHP arrays and objects,
   making them easier to work with than raw command output.

**Supported commands**

* start a working area
  * ✔ `git init`
  * ✔ `git clone`
* work on the current change
  * ✔ `git add`
  * ✔ `git mv`
  * ✔ `git restore`
  * ✔ `git rm`
  * 🛠 `git apply`
  * 🛠 `git stash`
    * 🛠 `git stash list`
    * 🛠 `git stash show`
    * 🛠 `git stash drop`
    * 🛠 `git stash pop`
    * 🛠 `git stash apply`
    * 🛠 `git stash branch`
    * 🛠 `git stash push`
    * 🛠 `git stash save`
    * 🛠 `git stash clear`
    * 🛠 `git stash create`
    * 🛠 `git stash store`
    * 🛠 `git stash export`
    * 🛠 `git stash import`
* examine the history and state
  * 🛠 `git bisect`
  * 🛠 `git diff`
  * ✔ `git diff --name-status --cached`
  * ✔ `git diff --name-status` (without `--cached`)
  * ✔ `git grep`
  * 🛠 `git log`
    * ✔ `git log --name-status` (src/Command/GetCommits.php)
    * 🛠 `git log --full-diff`
    * 🛠 `git log -L<start>,<end>:<file>`
    * 🛠 `git log -L:<funcname>:<file>`
  * 🛠 `git show`
    * ✔ `git show [ref]:<filePath>` (src/Command/GetFileContent.php)
  * ✔ `git status`
  * ✔ `git ls-files`
  * ✔ `git check-ignore`
  * ✔ `git check-attr`
* grow, mark and tweak your common history
  * 🛠 `git backfill`
  * ✔ `git branch CREATE`
  * ✔ `git branch --list --verbose`
  * ✔ `git branch --move`
  * ✔ `git branch --set-upstream-to`
  * ✔ `git branch --delete`
  * ✔ `git commit`
  * ✔ `git merge`
  * ✔ `git rebase`
  * ✔ `git reset`
  * 🛠 `git checkout`
  * ✔ `git symbolic-ref          <name> <ref>` UpsertSymbolicRef
  * ✔ `git symbolic-ref          <name>`       ReadSymbolicRef
  * ✔ `git symbolic-ref --delete <name>`       DeleteSymbolicRef
  * ✔ `git switch`
  * ✔ `git tag CREATE`
  * ✔ `git tag --list --verbose`
  * ✔ `git tag --delete`
* collaborate
  * 🛠 `git remote`
    * ✔ `git remote --verbose`
    * ✔ `git remote add`
    * ✔ `git remote rename`
    * ✔ `git remote remove`
    * ✔ `git remote update`
    * ✔ `git remote get-url` Fetch, Push
    * ✔ `git remote set-url` Set, Add, Delete
    * ✔ `git remote prune`
    * ✔ `git remote set-branches`
    * 🛠 `git remote set-head`
    * 🛠 `git remote show`
  * ✔ `git fetch`
  * ✔ `git pull`
  * ✔ `git push`
* config
  * ✔ `git config list`
  * ✔ `git config get`
  * ✔ `git config set`
  * ✔ `git config unset`
* other
  * ✔ `git --version`
  * ✔ `git --exec-path`
  * 🛠 `git fmt-merge-msg`
  * 🛠 and a lot of other commands


## stdInput reader


### stdInput reader - Supported Git hooks

* [post-receive](https://git-scm.com/docs/githooks#post-receive)
* [post-rewrite](https://git-scm.com/docs/githooks#_post_rewrite)
* [pre-push](https://git-scm.com/docs/githooks#_pre_push)
* [pre-receive](https://git-scm.com/docs/githooks#pre-receive)


### stdInput reader - Usage

**.git/hooks/pre-receive**
```PHP
#!/usr/bin/env php
<?php

use Sweetchuck\Git\StdInputReader\PreReceiveReader;

$reader = new PreReceiveReader(\STDIN);

foreach ($reader as $item) {
    echo 'Old value: ', $item->oldValue, \PHP_EOL;
    echo 'New value: ', $item->newValue, \PHP_EOL;
    echo 'Ref name:  ', $item->refName, \PHP_EOL;
    echo '-----------', \PHP_EOL;
}
```
