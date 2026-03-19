########## Variables de AWS ##########
variable "aws" {
   type = object({
      region                 = string
   })
}
########## Variables de VPC y todos los elementos que hay dentro ##########
variable "vpc-pub" {
   type = object({
      cidr                   = string
      tag = object({
         nombre              = string
         descripcion         = string
      })
      gtw = object({
         tag = object({
            nombre           = string
            descripcion      = string
         })
      })
      subred-01 = object({
         zona                = string
         tag = object({
            nombre           = string
            descripcion      = string
         })
         cidr                = string
         pub-map             = bool
      })
      subred-02 = object({
         zona                = string
         tag = object({
            nombre           = string
            descripcion      = string
         })
         cidr                = string
         pub-map             = bool
      })
      rutas = object({
         tag = object({
            nombre           = string
            descripcion      = string
         })
         routes = object({
            cidr-local       = string
            cidr-gtw         = string
         })
      })
      eks = object({
         nombre-efs-01       = string
         reglas = object({
            regla-in-todo = object({
               tipo          = string
               de_puerto     = number
               a_puerto      = number
               protocolo     = string
               cidr_blocks   = list(string)
            })
         })
         cluster = object({
            nombre           = string
            version          = string
            rol_cluster      = string
         })
         nodegroup = object({
            nombre           = string
            cluster          = string
            rol_nodo         = string
            tipos_ec2        = list(string)
            escalabilidad = object({
            deseada          = number
            max              = number
            min              = number
            })
         })
      })
   })
}