create database powershell_api;
create table powershell_api.usuarios (
    id INT auto_increment primary key,
    usuario varchar(20) not null unique,
    password varchar(255) not null,
    correo varchar(50) not null unique
);
create table powershell_api.credenciales (
    id int auto_increment primary key,
    nombre varchar(20) not null unique,
    valor varchar(255) not null unique
);
create table powershell_api.usuarios_credenciales (
    usuario_id int,
    credenciales_id int,
    primary key (usuario_id, credencial_id),
    foreign key (usuario_id) references powershell_api.usuarios(id) on delete cascade,
    foreign key (credencial_id) references powershell_api.credenciales(id) on delete cascade
);
insert into powershell_api.usuarios (usuario,password,correo) values ("admin","admin","");