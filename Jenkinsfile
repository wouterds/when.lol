import static Constants.*

class Constants {
  static final REPO = 'internal-whenlol-website'
  static final DOCKER_FOLDER = '~/docker/projects/' + REPO
  static final SERVER = 'server03.wouterdeschuyter.be'
}

node {
  try {
    stage('Checkout') {
      sh 'printenv'
      checkout scm
    }

    stage('Clean') {
      sh 'make clean'
    }

    stage('Dependencies') {
      sh 'make dependencies'
    }

    stage('Build') {
      sh 'make build'
    }

    stage('Deploy') {
      if (!env.BRANCH_NAME.equals('master')) {
        sh 'echo Not master branch, skip deploy.'
        return
      }

      sh 'echo Deploying production'
      deployProduction();
    }
  } catch (e) {
    throw e
  } finally {
    // Clean up
    cleanWorkspace()
  }
}

def deployProduction() {
  sh 'make push-latest'

  def folder = DOCKER_FOLDER + '-prod';

  sh 'ssh wouterds@'+SERVER+' "mkdir -p '+folder+'"'

  sh 'scp docker/docker-compose.yml wouterds@'+SERVER+':'+folder+'/docker-compose.yml'
  sh 'scp docker/docker-compose-prod.prod.yml wouterds@'+SERVER+':'+folder+'/docker-compose.prod.yml'
  sh 'scp docker/docker.prod.env wouterds@'+SERVER+':'+folder+'/docker.env'

  sh 'ssh wouterds@'+SERVER+' "cd '+folder+'; docker-compose -f docker-compose.yml -f docker-compose.prod.yml pull"'
  sh 'ssh wouterds@'+SERVER+' "cd '+folder+'; docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d"'

  sh 'ssh wouterds@'+SERVER+' "docker exec internalwhenlolwebsiteprod_php-fpm_1 php ./composer.phar migrations:migrate"'
}

def cleanWorkspace() {
  sh 'echo "Cleaning up workspace.."'
  deleteDir()
}
