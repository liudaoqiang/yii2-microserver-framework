# 接口定义，具体参数见apidoc生成的文档 #

----------

## 供货人(Provider) ##

`provider-info`: 

- show


## 管理人员或操作人员(User) ##

`user`: 

- login


## 药材(Herb) ##

`herb-info`: 

- show 
- show-places/:id
- show-soil-types
- show-herb-standards
- show-growth-cycles


## 加工基地，设备，区域，人员(Base) ##

`base-info`: 

- show
- show-base-machines-by-type
- show-base-workers-by-type
- show-base-areas-by-type


## 加工流程(ProcessTech) ##

`process-tech-info`: 

- show-names-by-herb
- show-by-herb-and-tech


## 加工数据(ProcessData) ##

`process-data-unhandled`: 

- show
- view/:id
- add

`process-data-handling`: 

- show
- view/:id
- add

`process-data-[step name]`: 

- show-by-handling-code
- show-prev-process-data-codes
- update/:id
- add

`process-data-handled`: 

- show
- view/:id
- show-by-trace-code(追溯码如何生成待确定)
- add

