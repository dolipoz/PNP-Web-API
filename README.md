# PNP-Web-API
Aplicación Web con funciones PNP.Powershell dentro de estructura AWS con Kubernetes

## Estructura del repositorio
### GitHub Actions Workflows
Existen varios workflows que permiten crear la infraestructura de AWS, los despliegues y lo necesario para que funcione la aplicación web menos el hosting.
Los secrets de repositorio de Github actions necesarios son los siguientes:
- TOKEN_GITHUB: Para usar el repositorio dentro del workflow es necesario generar un token desde la cuenta de github con permisos para manejar el repositorio
- AWS_ACCESS_KEY_ID: ID de cuenta AWS
- AWS_SECRET_ACCESS_KEY: KEY de cuenta AWS
- AWS_SESSION_TOKEN: TOKEN de cuenta AWS
- MYSQL_ROOT_PASSWORD: Contraseña para root de mysql
Las variables de repositorio de Github actions son:
- AWS_REGION: Región de cuenta AWS
- AWS_S3: Bucket utilizado en todos los workflows para almacenar tfstate y otros ficheros importantes
- HTTP_SERVER: Nombre de dominio que se haya obtenido antes para el hosting, Ej: dominio.com
- HTTP_ALIAS: Alias de la página web para el hosting utilizando el dominio ya obtenido, Ej: www.dominio.com
- ECR_Repo: Nombre del repositorio ECR que se creará dentro de la cuenta de AWS para almacenar las imagenes docker
Los workflows son los siguientes:
- S3-Crear: En caso de no tener un S3 propio, se puede lanzar este workflow para crearlo, si falla cambiar nombre del S3
- Terraform-Lanzar: Es el principal, permite crear toda la infraestructura
- Terraform-Destruir: Permite eliminar la infraestructura de AWS usando el tfstate almacenado en el bucket
- Terraform-Probar: Permite realizar un terraform plan para comprobar que todo va bien sin generar la infraestructura
### BASH
Directorio con scripts en lenguaje BASH utilizados dentro del proyecto tales como instalar docker y minikube
### Poweshell
Directorio con scripts en lenguaje Powershell utilizados dentro del proyecto
### HTML
Directorio con la estructura de la página web con php css
### Kubernetes
Directorio con ficheros necesarios para cargar deployments, services y pods
Se divide en:
- despliegues: Los dos despliegues, el de apache y el de mysql, cambiar el nombre de la imagen de cada uno ya que utilizan una variable que cambia según la imagen generada con docker dentro del workflow de github actions
- servicios: Los servicios de apache y de mysql
- volumenes: Los volumenes persistentes que se utilizarán para la base de datos y para la página html, en los volumenes persistentes se debe cambiar la variable que tiene por el id de EFS de AWS
