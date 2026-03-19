################## Grupos de Seguridad de EC2 en VPC 1 eks ##################
##### Grupo de seguridad de VPC #######
resource "aws_security_group" "grupo-eks" {
  tags = {
    Name                  = "GS eks"
    Description           = "Grupo de seguridad para subred eks en VPC Publica"
  }
  name                    = "grupo-eks"
  description             = "Permite todo el trafico de salida y de entrada web"
  vpc_id                  = aws_vpc.vpc-pub.id
  egress {
    from_port             = 0
    to_port               = 0
    protocol              = "-1"
    cidr_blocks           = ["0.0.0.0/0"]
  }

  ingress {
    from_port             = 0
    to_port               = 0
    protocol              = "-1"
    cidr_blocks           = ["0.0.0.0/0"]
  }

  depends_on = [
    aws_vpc.vpc-pub
  ]
}
