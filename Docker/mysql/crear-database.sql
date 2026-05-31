create database powershell_api;
use powershell_api;
-- Tabla para los roles de cada usuario
create table roles (
    id int auto_increment primary key,
    rol varchar(20) not null,
    descripcion varchar(100) default null,
    sistema boolean default false,
    unique (rol)
);
-- Tabla para los permisos que se asocian a los roles y permiten la gestión del programa
create table permisos (
    id int auto_increment primary key,
    permiso varchar(50) not null,
    descripcion varchar(100) default null,
    sistema boolean default false,
    unique (permiso)
);
-- Tabla para asociar los permisos a los roles
create table roles_permisos (
    id_rol int,
    id_permiso int,
    sistema boolean default false,

    primary key (id_rol, id_permiso),
    foreign key (id_rol) references roles(id) on delete cascade,
    foreign key (id_permiso) references permisos(id) on delete cascade
);
-- Tabla para los usuarios del sistema web
create table usuarios (
    id int auto_increment primary key,
    usuario varchar(20) not null,
    clave varchar(255) not null,
    correo varchar(50) default null,

    nombre varchar(20) default null,
    apellidos varchar(50) default null,

    activo boolean,
    id_rol int not null,
    f_creado datetime default current_timestamp,
    ult_sesion datetime default null,

    sistema boolean default false,

    foreign key (id_rol) references roles(id),
    unique (usuario)
);
-- Tabla para api de Microsoft
create table api (
    id int auto_increment primary key,
    tenant varchar(50) not null,
    sitio varchar(50) not null,
    id_cliente varchar(255) not null,

    unique (sitio)
);
-- Tabla para los certificados de las sesiones de PNP.Powershell
create table certificados (
    id int auto_increment primary key,
    nombre varchar(20) not null,
    contenido text default null,
    f_creado date default null,
    expira date default null,

    unique (nombre)
);
-- Tabla para asociar los certificados con las api
create table api_certificados (
    id_api int,
    id_certificado int,

    primary key (id_api, id_certificado),
    foreign key (id_api) references api(id) on delete cascade,
    foreign key (id_certificado) references certificados(id) on delete cascade
);
-- Tabla para los trabajos de powershell
create table trabajos (
    id int auto_increment primary key,
    id_api int deafult null,
    nombre_contenedor varchar(255) default null,
    id_trabajo int default null,
    trabajo json not null,
    estado varchar(20) not null,
    salida json default null,
    error varchar(255) default null,
    progreso int default 0,
    f_finalizacion datetime default null,
    bloqueo tinyint default null,

    foreign key (id_api) references api(id) on delete cascade,
    unique (id_api,bloqueo)
);
-- Añadimos el rol de admin con true en sistema para que no se pueda eliminar de los registros y el rol supervisor para
insert into roles (rol,descripcion,sistema) values ('admin','Puede gestionar todo dentro del sistema',true);
insert into roles (rol,descripcion) values ('tecnico','Rol modificable para gestiones de api y certificados');

delimiter //
create trigger permisos_admin_agregar
after insert on permisos
for each row
begin
    insert into roles_permisos (id_rol, id_permiso) values (1, new.id);
end;//
delimiter ;

-- Los primeros 9 permisos son para usuarios, roles y permisos
insert into permisos (permiso,descripcion,sistema) values ('usuarios.crear','Puede crear usuarios',true);
insert into permisos (permiso,descripcion,sistema) values ('usuarios.modificar','Puede modificar usuarios',true);
insert into permisos (permiso,descripcion,sistema) values ('usuarios.eliminar','Puede eliminar usuarios',true);
insert into permisos (permiso,descripcion,sistema) values ('roles.crear','Puede crear roles',true);
insert into permisos (permiso,descripcion,sistema) values ('roles.modificar','Puede modificar roles',true);
insert into permisos (permiso,descripcion,sistema) values ('roles.eliminar','Puede eliminar roles',true);
insert into permisos (permiso,descripcion,sistema) values ('permisos.crear','Puede crear roles',true);
insert into permisos (permiso,descripcion,sistema) values ('permisos.modificar','Puede modificar roles',true);
insert into permisos (permiso,descripcion,sistema) values ('permisos.eliminar','Puede eliminar roles',true);
-- De 10 a 15 son para api y certificados. En adelante se pueden agregar más para otras funciones.
insert into permisos (permiso,descripcion,sistema) values ('api.crear','Puede crear una api',true);
insert into permisos (permiso,descripcion,sistema) values ('api.modificar','Puede modificar una api',true);
insert into permisos (permiso,descripcion,sistema) values ('api.eliminar','Puede eliminar una api',true);
insert into permisos (permiso,descripcion,sistema) values ('certificados.crear','Puede crear certificados',true);
insert into permisos (permiso,descripcion,sistema) values ('certificados.modificar','Puede modificar certificados',true);
insert into permisos (permiso,descripcion,sistema) values ('certificados.eliminar','Puede eliminar certificados',true);

-- Añadimos los permisos al rol de técnico para que pueda gestionar api y certificados
insert into roles_permisos (id_rol, id_permiso) values (2, 10);
insert into roles_permisos (id_rol, id_permiso) values (2, 11);
insert into roles_permisos (id_rol, id_permiso) values (2, 12);
insert into roles_permisos (id_rol, id_permiso) values (2, 13);
insert into roles_permisos (id_rol, id_permiso) values (2, 14);
insert into roles_permisos (id_rol, id_permiso) values (2, 15);

-- Añadimos el usuario admin con contraseña "admin" hasheada, se debe modificar la constraseña tras iniciar sesión
insert into usuarios (
    usuario,
    clave,
    activo,
    id_rol,
    sistema
) values (
    'admin',
    '$2y$12$dueemzOHL6mFhiy0SkZpRe.47pIQHmoJ05dFhEj/RltFD5B5tO4jS',
    true,
    1,
    true
);

delimiter //
create trigger bloquear_modificar_usuarios
before update on usuarios
for each row
begin
    if old.sistema = true then
        set new.sistema = old.sistema;
        set new.id_rol = old.id_rol;
        set new.usuario = old.usuario;
        set new.activo = old.activo;
        set new.f_creado = old.f_creado;
    else
        set new.sistema = old.sistema;
        set new.f_creado = old.f_creado;
    end if;
end;//
create trigger bloquear_eliminar_usuarios
before delete on usuarios
for each row
begin
    if old.sistema = true then
        signal sqlstate '45000'
        set MESSAGE_TEXT = 'No se pueden modificar registros del sistema.';
    end if;
end;//
create trigger bloquear_modificar_roles
before update on roles
for each row
begin
    if old.sistema = true then
        signal sqlstate '45000'
        set MESSAGE_TEXT = 'No se pueden modificar registros del sistema.';
    end if;
end;//
create trigger bloquear_eliminar_roles
before delete on roles
for each row
begin
    if old.sistema = true then
        signal sqlstate '45000'
        set MESSAGE_TEXT = 'No se pueden modificar registros del sistema.';
    end if;
end;//
create trigger bloquear_modificar_permisos
before update on permisos
for each row
begin
    if old.sistema = true then
        signal sqlstate '45000'
        set MESSAGE_TEXT = 'No se pueden modificar registros del sistema.';
    end if;
end;//
create trigger bloquear_eliminar_permisos
before delete on permisos
for each row
begin
    if old.sistema = true then
        signal sqlstate '45000'
        set MESSAGE_TEXT = 'No se pueden modificar registros del sistema.';
    end if;
end;//
create trigger bloquear_modificar_rolespermisos
before update on roles_permisos
for each row
begin
    if old.sistema = true then
        signal sqlstate '45000'
        set MESSAGE_TEXT = 'No se pueden modificar registros del sistema.';
    end if;
end;//
create trigger bloquear_eliminar_rolespermisos
before delete on roles_permisos
for each row
begin
    if old.sistema = true then
        signal sqlstate '45000'
        set MESSAGE_TEXT = 'No se pueden modificar registros del sistema.';
    end if;
end;//
delimiter ;