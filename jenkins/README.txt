Setting up Jenkins for postilotta
=================================

1. run `./build_jenkins_docker.sh` and set REUSE_KEY=FALSE for new installation

2. Get init Admin password:
  ```
  docker exec -it pta_jenkins bash
  cat /var/jenkins_home/secrets/initialAdminPassword
  ```
2. Initialize Jenkins server on `jenkins.postilotta.com`
