create database powershell_api;
use powershell_api;
create table roles (
    id int auto_increment primary key,
    rol varchar(20) not null,
    descripcion varchar(100) default null,
    sistema boolean default false,
    unique (rol)
);
create table permisos (
    id int auto_increment primary key,
    permiso varchar(50) not null,
    descripcion varchar(100) default null,
    sistema boolean default false,
    unique (permiso)
);
create table roles_permisos (
    id_rol int,
    id_permiso int,
    sistema boolean default false,

    primary key (id_rol, id_permiso),
    foreign key (id_rol) references roles(id) on delete cascade,
    foreign key (id_permiso) references permisos(id) on delete cascade
);
create table usuarios (
    id int auto_increment primary key,
    usuario varchar(20) not null,
    clave varchar(255) not null,
    correo varchar(50) not null,

    nombre varchar(20) default null,
    apellidos varchar(50) default null,

    activo boolean,
    id_rol int not null,
    f_creado datetime default current_timestamp,
    ult_sesion datetime null,

    sistema boolean default false,

    foreign key (id_rol) references roles(id),
    unique (usuario),
    unique (correo)
);
create table api (
    id int auto_increment primary key,
    tenant varchar(50) not null,
    url_sharepoint varchar(255) not null,
    id_cliente varchar(255) not null
);
create table certificados (
    id int auto_increment primary key,
    id_api int not null,
    nombre varchar(20) not null,
    ruta varchar(255) not null,

    unique (nombre),
    unique (ruta)
);

insert into roles (rol,descripcion,sistema) values ('admin','Puede gestionar todo dentro del sistema',true);

delimiter //
create trigger permisos_admin_agregar
after insert on permisos
for each row
begin
    insert into roles_permisos (id_rol, id_permiso) values (1, new.id);
end;//
delimiter ;

insert into permisos (permiso,descripcion,sistema) values ('usuarios.crear','Puede crear usuarios',true);
insert into permisos (permiso,descripcion,sistema) values ('usuarios.modificar','Puede modificar usuarios',true);
insert into permisos (permiso,descripcion,sistema) values ('usuarios.eliminar','Puede eliminar usuarios',true);
insert into permisos (permiso,descripcion,sistema) values ('api.crear','Puede crear una api',true);
insert into permisos (permiso,descripcion,sistema) values ('api.modificar','Puede modificar una api',true);
insert into permisos (permiso,descripcion,sistema) values ('api.eliminar','Puede eliminar una api',true);
insert into permisos (permiso,descripcion,sistema) values ('certificados.crear','Puede crear certificados',true);
insert into permisos (permiso,descripcion,sistema) values ('certificados.modificar','Puede modificar certificados',true);
insert into permisos (permiso,descripcion,sistema) values ('certificados.eliminar','Puede eliminar certificados',true);
insert into permisos (permiso,descripcion,sistema) values ('roles.crear','Puede crear roles',true);
insert into permisos (permiso,descripcion,sistema) values ('roles.modificar','Puede modificar roles',true);
insert into permisos (permiso,descripcion,sistema) values ('roles.eliminar','Puede eliminar roles',true);
insert into permisos (permiso,descripcion,sistema) values ('permisos.crear','Puede crear roles',true);
insert into permisos (permiso,descripcion,sistema) values ('permisos.modificar','Puede modificar roles',true);
insert into permisos (permiso,descripcion,sistema) values ('permisos.eliminar','Puede eliminar roles',true);

insert into usuarios (
    usuario,
    clave,
    correo,
    activo,
    id_rol,
    ult_sesion,
    sistema
) values (
    'admin',
    'admin',
    'admin@email.com',
    true,
    1,
    null,
    true
);
