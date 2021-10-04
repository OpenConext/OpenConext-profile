#!/usr/bin/env bash

RELEASE_DIR=${HOME}/Releases
GITHUB_USER=OpenConext
PROJECT_NAME=OpenConext-profile

if [ -z "$1" ]
then

cat << EOF
Please specify the tag or branch to make a release of.

Examples:

    sh makeRelease.sh 0.1.0
    sh makeRelease.sh master
    sh makeRelease.sh develop

If you want to GPG sign the release, you can specify the "sign" parameter, this will
invoke the gpg command line tool to sign it.

   sh makeRelease 0.1.0 sign

EOF
exit 1
else
    TAG=$1
fi

PROJECT_DIR_NAME=${PROJECT_NAME}-${TAG//\//_} &&
PROJECT_DIR=${RELEASE_DIR}/${PROJECT_DIR_NAME} &&
SYMFONY_ENV=prod

echo "Preparing environment" &&
mkdir -p ${RELEASE_DIR} &&
rm -rf ${PROJECT_DIR} &&

echo "Cloning repository";
cd ${RELEASE_DIR} &&
git clone https://github.com/${GITHUB_USER}/${PROJECT_NAME}.git ${PROJECT_DIR_NAME} &&

echo "Checking out ${TAG}" &&
cd ${PROJECT_DIR} &&
git checkout ${TAG} &&

echo "Running Composer Install";
SYMFONY_ENV=${SYMFONY_ENV} composer install --no-dev --prefer-dist -o --no-scripts &&

echo "Running NPM install";
npm i &&
npm run build &&

echo "Tagging the release in RELEASE file" &&
COMMITHASH=`git rev-parse HEAD` &&
echo "Tag: ${TAG}" > ${PROJECT_DIR}/RELEASE &&
echo "Commit: ${COMMITHASH}" >> ${PROJECT_DIR}/RELEASE &&

echo "Cleaning build of dev files" &&
rm -f ${PROJECT_DIR}/.docheader &&
rm -f ${PROJECT_DIR}/.env.* &&
rm -f ${PROJECT_DIR}/.gitignore &&
rm -f ${PROJECT_DIR}/.scrutinizer.yml &&
rm -f ${PROJECT_DIR}/docker-compose.yml &&
rm -f ${PROJECT_DIR}/makeRelease.sh &&
rm -f ${PROJECT_DIR}/package.json &&
rm -f ${PROJECT_DIR}/package-lock.json &&
rm -f ${PROJECT_DIR}/phpcs.xml &&
rm -f ${PROJECT_DIR}/phpunit.xml &&
rm -f ${PROJECT_DIR}/phpmd.xml &&
rm -f ${PROJECT_DIR}/postcss.config.js &&
rm -f ${PROJECT_DIR}/symfony.lock &&
rm -f ${PROJECT_DIR}/webpack.config.js &&
rm -rf ${PROJECT_DIR}/.github &&
rm -rf ${PROJECT_DIR}/.git &&
rm -rf ${PROJECT_DIR}/ci &&
rm -rf ${PROJECT_DIR}/assets &&
rm -f ${PROJECT_DIR}/bin/extract-translations.sh &&
rm -rf ${PROJECT_DIR}/config/packages/dev &&
rm -rf ${PROJECT_DIR}/config/packages/test &&
rm -rf ${PROJECT_DIR}/cypress &&
rm -rf ${PROJECT_DIR}/docker &&
rm -rf ${PROJECT_DIR}/node_modules &&
rm -rf ${PROJECT_DIR}/tests &&

echo "Removing application cache, logs, bootstrap and parameters" &&
rm -rf ${PROJECT_DIR}/var/cache/* &&
rm -rf ${PROJECT_DIR}/var/logs/* &&

echo "Create tarball" &&
cd ${RELEASE_DIR} &&
tar -czf ${PROJECT_DIR_NAME}.tar.gz ${PROJECT_DIR_NAME}

echo "Create checksum file" &&
cd ${RELEASE_DIR} &&
if hash sha1sum 2>/dev/null; then
    sha1sum ${PROJECT_DIR_NAME}.tar.gz > ${PROJECT_DIR_NAME}.sha
else
    shasum ${PROJECT_DIR_NAME}.tar.gz > ${PROJECT_DIR_NAME}.sha
fi

if [ -n "$2" ]
then
	if [ "$2" == "sign" ]
	then
	    echo "Signing build"
		cd ${RELEASE_DIR}
		gpg -o ${PROJECT_DIR_NAME}.sha.gpg  --clearsign ${PROJECT_DIR_NAME}.sha
	fi
fi
