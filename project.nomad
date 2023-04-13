job "${CI_PROJECT_NAME}" {
    
    type = "service"
    datacenters = ["iutlens"]

    meta {
        git_sha = "[[${CI_COMMIT_SHORT_SHA}]]"
        }
    
    group "${CI_PROJECT_NAME}" {
        count = 1
        
        network {
            mode = "host"
            port "webserver" {
                to = 80
                }
        } # end of network declaration

    restart {
        attempts = 10
        delay = "10s"
        interval = "5m"
        mode = "delay"
    }

    update {
        max_parallel = 1
        min_healthy_time = "5s"
        healthy_deadline = "2m"
        canary = 0
        auto_revert = false
    }

    task "server" {
        driver = "docker"
        config {
            image = "https://${URL_REGISTRE}/${IMAGE}"
            auth {
                username = "${NEXUS_USER}"
                password = "${NEXUS_PWD}"
                }
            ports = ["webserver"]
        }
        
        resources {
            cpu = 128  # 128 Mhz
            memory = 128 # 128MB
        }

        logs {
            max_files = 5
            max_file_size = 15
        }

        service {
            name = "${CI_PROJECT_NAME}"
            port = "webserver"
            provider = "consul"

            check {
                name = "${CI_PROJECT_NAME}-check"
                type = "tcp"
                interval = "10s"
                timeout = "2s"
            }

            tags = [
                "traefik.enable=true",
                "traefik.http.routers.${CI_PROJECT_NAME}-nossl.rule=Host(`${URL_WEBSITE}`)",
                "traefik.http.routers.${CI_PROJECT_NAME}-nossl.entrypoints=web",
                "traefik.http.services.${CI_PROJECT_NAME}-nossl.loadbalancer.sticky",
                "traefik.http.routers.${CI_PROJECT_NAME}-secure.rule=Host(`${URL_WEBSITE}`)",
                "traefik.http.routers.${CI_PROJECT_NAME}-secure.entrypoints=websecure",
                "traefik.http.routers.${CI_PROJECT_NAME}-secure.tls=true",
                "traefik.http.routers.${CI_PROJECT_NAME}-secure.tls.options=intermediate@file",
                "traefik.http.services.${CI_PROJECT_NAME}-secure.loadbalancer.sticky",
            ]
        } # end of service declaration
    } # end of task declaration
    } # end of group declaration
} # end of job declaration
    