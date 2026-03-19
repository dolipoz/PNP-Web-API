resource "aws_eks_cluster" "eks-01" {
   name = var.vpc-pub.eks.cluster.nombre
   role_arn = var.vpc-pub.eks.cluster.rol_cluster
   version  = var.vpc-pub.eks.cluster.version
   vpc_config {
      subnet_ids = [
         aws_subnet.subred-eks-01.id,
         aws_subnet.subred-eks-02.id
      ]
   }

   depends_on = [
      aws_subnet.subred-eks-01,
      aws_subnet.subred-eks-02
   ]
}

resource "aws_eks_node_group" "eks-nodos" {
   cluster_name    = var.vpc-pub.eks.cluster.nombre
   node_group_name = var.vpc-pub.eks.nodegroup.nombre
   node_role_arn   = var.vpc-pub.eks.nodegroup.rol_nodo
   subnet_ids      = [
      aws_subnet.subred-eks-01.id,
      aws_subnet.subred-eks-02.id
   ]

   scaling_config {
      desired_size = var.vpc-pub.eks.nodegroup.escalabilidad.deseada
      max_size     = var.vpc-pub.eks.nodegroup.escalabilidad.max
      min_size     = var.vpc-pub.eks.nodegroup.escalabilidad.min
   }

   instance_types = var.vpc-pub.eks.nodegroup.tipos_ec2

   depends_on = [
      aws_eks_cluster.eks-01
   ]
}