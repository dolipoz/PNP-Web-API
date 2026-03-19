##################### Reglas de entrada para eks #####################
# resource "aws_security_group_rule" "regla-eks-in-todo" {
#   type              = var.eks.reglas.regla-in-todo.tipo
#   from_port         = var.eks.reglas.regla-in-todo.de_puerto
#   to_port           = var.eks.reglas.regla-in-todo.a_puerto
#   protocol          = var.eks.reglas.regla-in-todo.protocolo
#   cidr_blocks       = var.eks.reglas.regla-in-todo.cidr_blocks
#   security_group_id = aws_security_group.grupo-eks.id

#   depends_on = [
#     aws_security_group.grupo-eks
#   ]
# }
# resource "aws_security_group_rule" "regla-eks-in-http" {
#   type              = var.eks.reglas.regla-in-http.tipo
#   from_port         = var.eks.reglas.regla-in-http.de_puerto
#   to_port           = var.eks.reglas.regla-in-http.a_puerto
#   protocol          = var.eks.reglas.regla-in-http.protocolo
#   cidr_blocks       = var.eks.reglas.regla-in-http.cidr_blocks
#   security_group_id = aws_security_group.grupo-eks.id

#   depends_on = [
#     aws_security_group.grupo-eks
#   ]
# }
# resource "aws_security_group_rule" "regla-eks-in-https" {
#   type              = var.eks.reglas.regla-in-https.tipo
#   from_port         = var.eks.reglas.regla-in-https.de_puerto
#   to_port           = var.eks.reglas.regla-in-https.a_puerto
#   protocol          = var.eks.reglas.regla-in-https.protocolo
#   cidr_blocks       = var.eks.reglas.regla-in-https.cidr_blocks
#   security_group_id = aws_security_group.grupo-eks.id

#   depends_on = [
#     aws_security_group.grupo-eks
#   ]
# }
