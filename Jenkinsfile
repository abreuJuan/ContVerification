pipeline {
    agent { label 'hitlab-dev-docker' }
    environment {
        REGISTER='drhit.azurecr.io'
        DOCKER_USERNAME = 'drhit'
        DOCKER_PASSWORD = 'N0osOuQYC5tQQu10=2DxqTVi5jY9SCNn'
        IMAGE_NAME='w_solicitud'
    }
    stages {
        stage('Building image') {
            when {
                branch 'master'
            }
            steps{
                sh ' echo login to registry'
                sh 'docker login $REGISTER --username $DOCKER_USERNAME --password $DOCKER_PASSWORD'

                sh ' echo building dockerfile'
                sh 'docker build -t $IMAGE_NAME .'

       

            }

        }
        stage('Pushing images to registry')
        {
            when {
                branch 'master'
            }
           steps {
               // sh 'docker-compose up -e ENVIROMENT="DEV"'
              // sh 'docker system prune -a --volumes --force'

               sh 'echo tag image $IMAGE_NAME'
               sh 'docker tag $IMAGE_NAME $REGISTER/$IMAGE_NAME'

               sh 'echo push image'
               sh 'docker push $REGISTER/$IMAGE_NAME'

              // sh 'docker build -t $REGISTER/$IMAGE_NAME .'


            }
        }
        stage('Deploy development'){
            steps {
               sh 'docker-compose up -d'
            }
        }
/*        stage('Deliver for QA') {
            when {
                branch 'QA'
            }
            steps {
              // sh 'docker-compose up -e ENVIROMENT="QA"'
              sh ' echo 2'
            }
        }
        stage('Deploy for master') {
            when {
                branch 'master'
            }
            steps {
               // sh 'docker-compose up -e ENVIROMENT="PRO"'
               sh ' echo 3'
            }
        }
*/
    }
}
