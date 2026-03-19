###################### Enrutamiento de VPC para eks ##################################
##### Tabla de Enrutamiento de subredes eks #####
resource "aws_route_table" "rutas-pub" {
  vpc_id                  = aws_vpc.vpc-pub.id
  route {
    cidr_block            = var.vpc-pub.rutas.routes.cidr-local
    gateway_id            = "local"
  }
  route {
    cidr_block            = var.vpc-pub.rutas.routes.cidr-gtw
    gateway_id            = aws_internet_gateway.gtw-pub.id
  }
  tags = {
    Name                  = var.vpc-pub.rutas.tag.nombre
    Description           = var.vpc-pub.rutas.tag.descripcion
  }
  depends_on = [
    aws_vpc.vpc-pub,
    aws_internet_gateway.gtw-pub
  ]
}
##### Asociar tabla de enrutamiento con subred eks #####
resource "aws_route_table_association" "arta-eks-01" {
  subnet_id               = aws_subnet.subred-eks-01.id
  route_table_id          = aws_route_table.rutas-pub.id
  depends_on = [
    aws_subnet.subred-eks-01,
    aws_route_table.rutas-pub
  ]
}
resource "aws_route_table_association" "arta-eks-02" {
  subnet_id               = aws_subnet.subred-eks-02.id
  route_table_id          = aws_route_table.rutas-pub.id
  depends_on = [
    aws_subnet.subred-eks-02,
    aws_route_table.rutas-pub
  ]
}