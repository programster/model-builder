<?php

/* 
 * Run the scripts in the seeds folder in order to seed the database with some testing/fake data.
 */

require_once(__DIR__ . '/../bootstrap.php');

$seedScripts = Programster\CoreLibs\Filesystem::getDirContents(
    dir: __DIR__ . '/../seeds', 
    includePath: true,
    recursive: false,
    onlyFiles: true,
    includeHiddenFilesAndFolders: false
);

foreach ($seedScripts as $seedScript)
{
    if (\Programster\CoreLibs\StringLib::endsWith($seedScript, ".php"))
    {
        require_once($seedScript);
    }
}
