################### VPC para EKS #################
resource "aws_vpc" "vpc-pub" {
  cidr_block              = var.vpc-pub.cidr
  enable_dns_support      = true
  enable_dns_hostnames    = true
  tags = {
    Name                  = var.vpc-pub.tag.nombre
    Description           = var.vpc-pub.tag.descripcion
  }
}
##### Puerta de enlace #####
resource "aws_internet_gateway" "gtw-pub" {
  vpc_id                  = aws_vpc.vpc-pub.id
  tags = {
    Name                  = var.vpc-pub.gtw.tag.nombre
    Description           = var.vpc-pub.gtw.tag.descripcion
  }
  depends_on = [
    aws_vpc.vpc-pub
  ]
}
