########## Valores de AWS ##########
aws = {
   region                  = "us-east-1"
}
########## Valores de VPC-PUB ##########
vpc-pub = {
   cidr                    = "172.16.0.0/16"
   tag = {
      nombre               = "VPC Publica"
      descripcion          = "VPC Publica para subredes eks"
   }
   gtw = {
      tag = {
         nombre            = "Gateway eks"
         descripcion       = "Gateway para eks en VPC publica"
      }
   }
   subred-01 = {
      zona                 = "us-east-1a"
      tag = {
         nombre            = "Subred 1 eks"
         descripcion       = "Subred 1 eks sobre VPC publica"
      }
      cidr                 = "172.16.1.0/24"
      pub-map              = true
   }
   subred-02 = {
      zona                 = "us-east-1b"
      tag = {
         nombre            = "Subred 2 eks"
         descripcion       = "Subred 2 eks sobre VPC publica"
      }
      cidr                 = "172.16.2.0/24"
      pub-map              = true
   }
   rutas = {
      tag = {
         nombre            = "Tabla de Enrutamiento eks"
         descripcion       = "Tabla de Enrutamiento de VPC Publica para subredes eks"
      }
      routes = {
         cidr-local        = "172.16.0.0/16"
         cidr-gtw          = "0.0.0.0/0"
      }
   }
   eks = {
      nombre-efs-01        = "efs-01"
      reglas = {
         regla-in-todo = {
            tipo           = "ingress"
            de_puerto      = -1
            a_puerto       = -1
            protocolo      = "tcp"
            cidr_blocks    = ["0.0.0.0/0"]
         }
      }
      cluster = {
         nombre            = "eks-01"
         version           = "1.35"
         rol_cluster       = "arn:aws:iam::533267229166:role/LabRole"
      }
      nodegroup = {
         nombre            = "NG-01"
         cluster           = "eks-01"
         rol_nodo          = "arn:aws:iam::533267229166:role/LabRole"
         tipos_ec2         = ["t3.medium"]
         escalabilidad = {
            deseada        = 2
            max            = 2
            min            = 1
         }
      }
   }
}