############## Muestra de Datos ###############
output "nombre_cluster" {
   description = "Nombre del cluster"
   value = ["${aws_eks_cluster.eks-01.id}"]
   depends_on = [
      aws_eks_cluster.eks-01
   ]
}
output "id_efs" {
   description = "Id de efs"
   value = ["${aws_efs_file_system.efs-01.id}"]
   depends_on = [
      aws_efs_file_system.efs-01
   ]
}