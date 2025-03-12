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

/*consultas*/
/*se agrega columna para guardar hora fin de la clase*/
ALTER TABLE clases ADD COLUMN hora_fin TIME NOT NULL AFTER hora;

/*muestra la asignatura, fecha, hora, hora fin y el profesor para obtener las clases y el estado
(activa/cancelada).*/

SELECT c.id, a.nombre as asignatura, c.fecha, c.hora, c.hora_fin, c.estado, u.nombres as profesor 
        FROM clases c
        INNER JOIN asignaturas a ON c.asignatura_id = a.id 
        INNER JOIN usuarios u ON c.profesor_id = u.id;
        
/*DESCRIBE clases;*/

/*se usa para mostrar las clases creadas y asi poder asignarle estudiantes*/
SELECT c.id, a.nombre as asignatura, c.fecha, c.hora, c.hora_fin, u.nombres as profesor
               FROM clases c
               INNER JOIN asignaturas a ON c.asignatura_id = a.id
               INNER JOIN usuarios u ON c.profesor_id = u.id;

/*se agrega columna estado para las clases activa o cancelada*/
alter table clases add column estado enum('activa', 'cancelada') not null default 'activa';

/*muestra con cantidad de estudiantes*/
SELECT 		c.id, 
            a.nombre as asignatura, 
            c.fecha, 
            c.hora, 
            c.hora_fin, 
            u.nombres as profesor,
            (SELECT COUNT(*) FROM inscripciones WHERE clase_id = c.id) as estudiantes
        FROM clases c
        INNER JOIN asignaturas a ON c.asignatura_id = a.id 
        INNER JOIN usuarios u ON c.profesor_id = u.id;

SELECT c.id, a.nombre as asignatura, c.fecha, c.hora, c.hora_fin, c.estado, u.nombres as profesor 
            FROM clases c
            INNER JOIN asignaturas a ON c.asignatura_id = a.id 
            INNER JOIN usuarios u ON c.profesor_id = u.id
            WHERE c.profesor_id = '11';
            
SELECT c.id, a.nombre as asignatura, c.fecha, c.hora, c.hora_fin, c.estado, u.nombres as profesor 
            FROM clases c
            INNER JOIN asignaturas a ON c.asignatura_id = a.id 
            INNER JOIN usuarios u ON c.profesor_id = u.id
            INNER JOIN inscripciones i ON c.id = i.clase_id
            WHERE i.estudiante_id ='15';
            







        






