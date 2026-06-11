# PNP-Web-API
Aplicación Web con funciones PNP.Powershell dentro de estructura AWS con Kubernetes

# Estructura del repositorio
## GitHub Actions Workflows
Existen varios workflows que permiten crear la infraestructura de AWS, los despliegues y lo necesario para que funcione la aplicación web menos el hosting.
### Los secrets de repositorio de Github actions necesarios son los siguientes:
- TOKEN_GITHUB: Para usar el repositorio dentro del workflow es necesario generar un token desde la cuenta de github con permisos para manejar el repositorio
- AWS_ACCESS_KEY_ID: ID de cuenta AWS
- AWS_SECRET_ACCESS_KEY: KEY de cuenta AWS
- AWS_SESSION_TOKEN: TOKEN de cuenta AWS
- MYSQL_ROOT_PASSWORD: Contraseña para root de mysql
- API_PASS: Clave del usuario de PHP para conectarse a la base de datos
- PWSH_PASS: Clave del usuario Powershell para conectarse a la base de datos
### Las variables de repositorio de GitHub Actions son:
- AWS_REGION: Región de cuenta AWS
- AWS_S3: Bucket utilizado en todos los workflows para almacenar tfstate y otros ficheros importantes
- AWS_CERTIFICADO: Nombre del Certificado importado a AWS con la etiqueta "Nombre"
- HTTP_SERVER: Nombre de dominio que se haya obtenido antes para el hosting, Ej: dominio.com
- HTTP_ALIAS: Alias de la página web para el hosting utilizando el dominio ya obtenido, Ej: www.dominio.com
- ECR_Repo: Nombre del repositorio ECR que se creará dentro de la cuenta de AWS para almacenar las imagenes docker
- API_USER: Nombre del usuario de PHP para conectarse a la base de datos
- PWSH_USER: Nombre del usuario Powershell para conectarse a la base de datos
### Los workflows son los siguientes:
- S3-Crear: En caso de no tener un S3 propio, se puede lanzar este workflow para crearlo, si falla cambiar nombre del S3
- ECR-Repo-Crear: En caso de no tener un Repositorio ECR propio, se puede lanzar este workflow para crearlo
- Terraform-Lanzar: Es el principal, permite crear toda la infraestructura
- Terraform-Destruir: Permite eliminar la infraestructura de AWS usando el tfstate almacenado en el bucket
- Terraform-Probar: Permite realizar un terraform plan para comprobar que todo va bien sin generar la infraestructura
## Docker
Directorio con scripts en lenguaje BASH utilizados dentro del proyecto tales como instalar docker y minikube
### Apache
Directorio de la imagen para PHP con el archivo https.conf de apache y dockerfile
### MySQL
Directorio de la imagen MySQL con dockerfile y el script de la creación de la base de datos, tablas y triggers
### Poweshell
Directorio de la imagen Powershell con dockerfile y la carpeta de scripts en lenguaje Powershell utilizados dentro del proyecto
## HTML
Directorio con la estructura de la página web con PHP y CSS
## Kubernetes
Directorio con ficheros para el despliegue de Apache, Powershell y MySQL con sus respectivos servicios de MySQL como ClusterIP y Apache en LoadBalancer.
### Se divide en:
- Despliegues: Los dos despliegues, el de apache y el de mysql, cambiar el nombre de la imagen de cada uno ya que utilizan una variable que cambia según la imagen generada con docker dentro del workflow de github actions
- Servicios: Los servicios de apache y de mysql
- Volúmenes: Los volúmenes persistentes que se utilizarán para la base de datos y para los certificados de Powershell.
## Terraform
Se utiliza terraform.tfvars para cambiar variables de nombres y valores de la infraestructura
Para la infraestructura en AWS se utiliza terraform, la estructura consta de lo siguiente:
- VPC: Un vpc con acceso público para permitir el acceso web
- 2 Subredes públicas: EKS requiere de al menos 2 subredes para disponibilidad
- EFS: Para los volúmenes persistentes se utilizará un EFS que sirve de almacenamiento en red
- EKS: El cluster para el despliegue de Kubernetes
- Output: Permite mostrar y almacenar en variables los nombres del cluster y del EFS para el script en GitHub Actions
## Documentos
En Documentos hay almacenado un fichero Excel con la plantilla de la estructura de Documentos Compartidos en el Sharepoint, además de un CSV de ejemplo exportado del mismo Excel con el separador ";"




