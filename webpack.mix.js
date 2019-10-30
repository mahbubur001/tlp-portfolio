const mix = require('laravel-mix');
const fs = require("fs-extra");
const path = require("path");
const cliColor = require("cli-color");
const emojic = require("emojic");
const wpPot = require('wp-pot');
const os = require('os');
const archiver = require('archiver');


const package_path = path.resolve(__dirname);
const package_slug = path.basename(path.resolve(package_path));
if (process.env.NODE_ENV === 'package') {

    mix.then(function () {

        const copyTo = path.resolve(`${package_slug}`);
        // Select All file then paste on list
        let includes = [
            'assets',
            'languages',
            'lib',
            'index.php',
            'README.txt',
            `${package_slug}.php`];
        fs.ensureDir(copyTo, function (err) {
            if (err) return console.error(err);
            includes.map(include => {
                fs.copy(`${package_path}/${include}`, `${copyTo}/${include}`, function (err) {
                    if (err) return console.error(err);
                    console.log(cliColor.white(`=> ${emojic.smiley}  ${include} copied...`));
                })
            });
            console.log(cliColor.white(`=> ${emojic.whiteCheckMark}  Build directory created`));
        });
    });

    return;
}
if (process.env.NODE_ENV === 'production') {

    if (Mix.inProduction()) {
        let languages = path.resolve('languages');
        fs.ensureDir(languages, function (err) {
            if (err) return console.error(err); // if file or folder does not exist
            wpPot({
                package: 'TLP Portfolio',
                bugReport: '',
                src: '**/*.php',
                domain: package_slug,
                destFile: `languages/${package_slug}.pot`
            });
        });

    }


    mix.js(`dist/blocks.build.js`, `assets/js/tlp-portfolio-blocks.min.js`);
}

if (process.env.NODE_ENV === 'zip') {
    const sourcePath = `${package_path}/${package_slug}.zip`;
    const desktop = os.homedir() + '/Desktop';
    const destinationPath = `${desktop}/${package_slug}.zip`;
    const output = fs.createWriteStream(sourcePath);
    const archive = archiver('zip');
    output.on('close', function () {
        console.log(archive.pointer() + ' total bytes');
        console.log('Archive has been finalized and the output file descriptor has closed.');
        fs.removeSync(`${package_path}/${package_slug}`);
        fs.move(sourcePath, destinationPath, err => {
            if (err) return console.error(err);
            console.log('success!')
        });
    });

    archive.on('error', function (err) {
        throw err;
    });

    archive.pipe(output);
    archive.directory(`${package_path}/${package_slug}`, '');
    archive.finalize();
}
