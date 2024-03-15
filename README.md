# Git related helper classes

[![CircleCI](https://circleci.com/gh/Sweetchuck/git.svg?style=svg)](https://circleci.com/gh/Sweetchuck/git)
[![codecov](https://codecov.io/gh/Sweetchuck/git/branch/master/graph/badge.svg)](https://codecov.io/gh/Sweetchuck/git)


## stdInput reader

## stdInput reader - Supported Git hooks

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
