<?php

namespace Lazzier\Contracts;

interface YamlKey
{
    const ROOT_DIR = 'root_dir';

    const RELEASES_DIR = 'releases_dir';

    const RELEASE_LINK = 'release_link';

    const SHARE_DIRS = 'share_dirs';

    const COPY_FILES = 'copy_files';

    const LINK_FILES = 'link_files';

    const MAKE_DIRS = 'make_dirs';

    const CUSTOMS = 'customs';

    const PACKAGE_FORMAT = 'package_format';

    const BEFORE = 'before';

    const AFTER = 'after';

    const SCHEDULE = 'schedule';

    const SOURCE = 'source';

    const TARGET = 'target';

    const TASK = 'task';

    const ARGS = 'args';

    const INSTALL = 'install';

    const UNINSTALL = 'uninstall';

    const UNPACK_ARTIFACT = 'unpack_artifact';

    const LINK_CURRENT = 'link_current';
}
