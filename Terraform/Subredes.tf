################### Subredes dentro de VPC-PUB para disponibilidad, mínimo 2 ###################
##### Subred 01 para eks en una zona #####
resource "aws_subnet" "subred-eks-01" {
  vpc_id                  = aws_vpc.vpc-pub.id
  cidr_block              = var.vpc-pub.subred-01.cidr
  map_public_ip_on_launch = var.vpc-pub.subred-01.pub-map
  availability_zone       = var.vpc-pub.subred-01.zona
  tags = {
    Name                  = var.vpc-pub.subred-01.tag.nombre
    Description           = var.vpc-pub.subred-01.tag.descripcion
  }
  depends_on = [
    aws_vpc.vpc-pub
  ]
}
##### Subred 02 para eks en otra zona #####
resource "aws_subnet" "subred-eks-02" {
  vpc_id                  = aws_vpc.vpc-pub.id
  cidr_block              = var.vpc-pub.subred-02.cidr
  map_public_ip_on_launch = var.vpc-pub.subred-02.pub-map
  availability_zone       = var.vpc-pub.subred-02.zona
  tags = {
    Name                  = var.vpc-pub.subred-02.tag.nombre
    Description           = var.vpc-pub.subred-02.tag.descripcion
  }
  depends_on = [
    aws_vpc.vpc-pub
  ]
}