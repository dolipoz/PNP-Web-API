#### Recurso de AWS - Sistema de ficheros compartido para los volumenes persistentes de EKS
resource "aws_efs_file_system" "efs-01" {
  creation_token    = var.vpc-pub.eks.nombre-efs-01
  tags = {
    Name            = var.vpc-pub.eks.nombre-efs-01
  }
}

#### Montaje para subred 1
resource "aws_efs_mount_target" "montaje-01" {
  file_system_id    = aws_efs_file_system.efs-01.id
  subnet_id         = aws_subnet.subred-eks-01.id
  security_groups   = [ aws_security_group.grupo-eks.id ]
  depends_on = [
    aws_efs_file_system.efs-01,
    aws_security_group.grupo-eks,
    aws_subnet.subred-eks-01
  ]
}
#### Zona de montaje de efs-01 para subred 2
resource "aws_efs_mount_target" "montaje-02" {
  file_system_id    = aws_efs_file_system.efs-01.id
  subnet_id         = aws_subnet.subred-eks-02.id
  security_groups   = [ aws_security_group.grupo-eks.id ]
  depends_on = [
    aws_efs_file_system.efs-01,
    aws_security_group.grupo-eks,
    aws_subnet.subred-eks-02
  ]
}