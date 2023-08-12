CREATE DATABASE panchavati;
/*DROP DATABASE panchavati;*/
USE panchavati;

CREATE TABLE admin (
	ApID INTEGER PRIMARY KEY AUTO_INCREMENT,
    admin_id VARCHAR(100),
    admin_pwd TEXT
);
ALTER TABLE admin ADD COLUMN devAdmin BOOL AFTER admin_pwd;
ALTER TABLE admin ADD COLUMN devAdminAgreed BOOL AFTER devAdmin;
ALTER TABLE admin ADD COLUMN managingAdmin BOOL AFTER devAdminAgreed;
ALTER TABLE admin ADD COLUMN regPermit BOOL AFTER managingAdmin;
SELECT * FROM admin;
INSERT INTO admin(admin_id, admin_pwd) VALUES('7013328951ADMIN', 'pwd'), ('7569367540ADMIN', 'pwd');
UPDATE admin SET managingAdmin=1, regPermit=1 WHERE ApID > 0;

CREATE TABLE student (
	SpID INTEGER PRIMARY KEY AUTO_INCREMENT,
    stu_id VARCHAR(100),
    stu_pwd TEXT,
    stu_name VARCHAR(150),
    course VARCHAR(30)
);
SELECT * FROM student;
DELETE FROM student WHERE SpID>0;
ALTER TABLE student ADD COLUMN blocked BOOL AFTER course;
SELECT * FROM student;
/*INSERT INTO student(stu_id, stu_pwd, stu_name, course) VALUES('7013328951STUBPC', 'pwd', 'Sai Uttarkar', 'BPC'), ('7569367540STUMPC', 'pwd', 'Jishnu Uttarkar', 'MPC');*/

CREATE TABLE student_login (
	LpID INTEGER PRIMARY KEY AUTO_INCREMENT,
    loginBy INTEGER,
    login_date DATE,
    login_time TIME,
    FOREIGN KEY(loginBy) REFERENCES student(SpID) ON DELETE CASCADE
);
DROP TABLE student_login;

CREATE TABLE scheduledExams (
	EpID INTEGER PRIMARY KEY AUTO_INCREMENT,
    examID VARCHAR(100),
    examSubject_name VARCHAR(70),
	examDate DATE,
    examTime TIME
);
ALTER TABLE scheduledExams ADD COLUMN examTime_to TIME AFTER examTime;
ALTER TABLE scheduledExams ADD COLUMN pm_am VARCHAR(4) AFTER examTime_to;
ALTER TABLE scheduledExams ADD COLUMN pm_am_to VARCHAR(4) AFTER pm_am;
ALTER TABLE scheduledExams ADD COLUMN note VARCHAR(500) AFTER pm_am_to;

CREATE TABLE examQP (
	EQpID INTEGER PRIMARY KEY AUTO_INCREMENT,
    ofExam INTEGER,
    QP BLOB,
    FOREIGN KEY(ofExam) REFERENCES scheduledExams(EpID) ON DELETE CASCADE
);
ALTER TABLE examQP ADD COLUMN negativeMarking BOOL AFTER QP;
ALTER TABLE examQP ADD COLUMN negativeMarks FLOAT AFTER negativeMarking;
ALTER TABLE examQP ADD COLUMN positiveMarks FLOAT AFTER negativeMarks;

CREATE TABLE options (
	OpID INTEGER PRIMARY KEY AUTO_INCREMENT,
    exam INTEGER,
    questionNum INTEGER,
    correctOption VARCHAR(3),
    FOREIGN KEY(exam) REFERENCES examQP(EQpID) ON DELETE CASCADE
);
ALTER TABLE options MODIFY COLUMN correctOption VARCHAR(10);
ALTER TABLE options ADD COLUMN startQuestion INTEGER AFTER correctOption;
ALTER TABLE options ADD COLUMN endQuestion INTEGER AFTER startQuestion;

CREATE TABLE results (
	RpID INTEGER PRIMARY KEY AUTO_INCREMENT,
    exam INTEGER,
    student INTEGER,
    score INTEGER,
    max_marks INTEGER,
    FOREIGN KEY(exam) REFERENCES scheduledExams(EpID),
    FOREIGN KEY(student) REFERENCES student(SpID) ON DELETE CASCADE
);

CREATE TABLE answerKeys (
	KpID INTEGER PRIMARY KEY AUTO_INCREMENT,
    key_for INTEGER,
    keyFile BLOB,
    FOREIGN KEY(key_for) REFERENCES scheduledExams(EpID)
);
ALTER TABLE answerKeys ADD COLUMN solFile BLOB AFTER keyFile;
DESC answerKeys;







