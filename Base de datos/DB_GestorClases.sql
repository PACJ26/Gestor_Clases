create database	 Gestor_Clases;
use Gestor_Clases;

create table roles(
id int auto_increment primary key,
nombre varchar(50) unique not null
);

create table tipos_documento(
id int auto_increment primary key,
tipo varchar(50) unique not null
);

create table usuarios(
id int auto_increment primary key,
nombres varchar(100) not null,
apellidos varchar(100) not null,
documento varchar(50) unique not null,
telefono varchar(20)not null,
correo varchar(100) unique not null,
contraseña varchar(255) not null,
rol_id int not null,
tipo_documento_id int not null,
foreign key (rol_id) references roles(id),
foreign key (tipo_documento_id) references tipos_documento(id)
);

create table asignaturas(
id int auto_increment primary key,
nombre varchar(50) unique not null
);

create table clases(
id int auto_increment primary key,
asignatura_id int not null,
profesor_id int not null,
fecha date not null,
hora time not null,
foreign key (asignatura_id) references asignaturas(id),
foreign key (profesor_id) references usuarios(id)
);

create table inscripciones(
id int auto_increment primary key,
estudiante_id int not null,
clase_id int not null,
foreign key (estudiante_id) references usuarios(id),
foreign key (clase_id)references clases(id)
);

