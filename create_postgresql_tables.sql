
-- Table: public.finance_info

-- DROP TABLE public.finance_info;

CREATE TABLE public.finance_info
(
  iin_bin bigint NOT NULL,
  name_ru character varying(1024),
  total_arrear numeric(10,2) DEFAULT 0,
  total_tax_arrear numeric(10,2) DEFAULT 0,
  pension_contribution_arrear numeric(10,2) DEFAULT 0,
  social_contribution_arrear numeric(10,2) DEFAULT 0,
  social_health_insurance_arrear numeric(10,2) DEFAULT 0,
  CONSTRAINT finance_info_pkey PRIMARY KEY (iin_bin)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.finance_info
  OWNER TO postgres;




-- Sequence: public.tax_org_info_id_seq

-- DROP SEQUENCE public.tax_org_info_id_seq;

CREATE SEQUENCE public.tax_org_info_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
ALTER TABLE public.tax_org_info_id_seq
  OWNER TO postgres;


-- Table: public.tax_org_info

-- DROP TABLE public.tax_org_info;

CREATE TABLE public.tax_org_info
(
  id integer NOT NULL DEFAULT nextval('tax_org_info_id_seq'::regclass),
  iin_bin bigint NOT NULL,
  name_ru character varying(1024),
  char_code integer,
  report_acrual_date bigint,
  total_arrear numeric(10,2),
  total_tax_arrear numeric(10,2),
  pension_contribution_arrear numeric(10,2),
  social_contribution_arrear numeric(10,2),
  social_health_insurance_arrear numeric(10,2),  
  CONSTRAINT tax_org_info_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.tax_org_info
  OWNER TO postgres;





-- Sequence: public.tax_payer_info_id_seq

-- DROP SEQUENCE public.tax_payer_info_id_seq;

CREATE SEQUENCE public.tax_payer_info_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
ALTER TABLE public.tax_payer_info_id_seq
  OWNER TO postgres;
  

-- Table: public.tax_payer_info

-- DROP TABLE public.tax_payer_info;

CREATE TABLE public.tax_payer_info
(
  id integer NOT NULL DEFAULT nextval('tax_payer_info_id_seq'::regclass),
  tax_org_info_id bigint NOT NULL,
  iin_bin integer NOT NULL,
  name_ru character varying(1024),
  total_arrear numeric(10,2),
  CONSTRAINT tax_payer_info_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.tax_payer_info
  OWNER TO postgres;






-- Sequence: public.bcc_arrears_info_id_seq

-- DROP SEQUENCE public.bcc_arrears_info_id_seq;

CREATE SEQUENCE public.bcc_arrears_info_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
ALTER TABLE public.bcc_arrears_info_id_seq
  OWNER TO postgres;


-- Table: public.bcc_arrears_info

-- DROP TABLE public.bcc_arrears_info;

CREATE TABLE public.bcc_arrears_info
(
  id integer NOT NULL DEFAULT nextval('bcc_arrears_info_id_seq'::regclass),
  tax_payer_info_id bigint NOT NULL,
  bcc integer,
  bcc_name_ru character varying(1024),
  tax_arrear numeric(10,2),
  poena_arrear numeric(10,2),
  percent_arrear numeric(10,2),
  fine_arrear numeric(10,2),
  total_arrear numeric(10,2),
  CONSTRAINT bcc_arrears_info_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.bcc_arrears_info
  OWNER TO postgres;
       