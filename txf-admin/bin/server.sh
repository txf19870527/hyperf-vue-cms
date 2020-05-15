#!/bin/bash
basepath=$(cd `dirname $0`; cd ..; pwd)
serverfile="${basepath}/bin/hyperf.php"

cd $basepath

# 重置文件缓存
initproxy(){
  rm -rf runtime/container
  echo "Runtime cleared"
  php "${serverfile}" di:init-proxy
  echo "Init proxy runtime created"
  return $!
}

# 停止服务
stop(){

  # 判断主进程如果存在
  if [ -f "runtime/hyperf.pid" ];then
    cat runtime/hyperf.pid | awk '{print $1}' | xargs kill && rm -rf runtime/hyperf.pid
  fi

  # 判断是否有残留进程，通知退出
  local num=`count`
  while [ $num -gt 0 ]; do
    echo "The worker num:${num}"
    ps -ef | grep "${serverfile}" | grep -v "grep"| awk '{print $2}'| xargs kill
    num=`count`
    sleep 1
  done

  echo "Stop!"
  return $!
}

# 进程数
count()
{
  echo `ps -fe |grep "${serverfile}" | grep -v "grep"| wc -l`
}

#查看状态
status(){
  local num=`count`
  if [ $num -gt 0 ];then
    if [ -f "runtime/hyperf.pid" ];then
      local pid=" pid:`cat runtime/hyperf.pid | awk '{print $1}'`"
    fi
    echo "Running!${pid} worker num:${num}"
  else
    echo "Close!"
  fi
  return $!
}

# 启动服务
start()
{
  local num=`count`
  if [ $num -gt 0 ];then
    status
    return $!
  fi
  initproxy
  exec php "${serverfile}" start
  echo "Start!"
  return $!
}

# 帮助文档
help()
{
    cat <<- EOF
    Usage:
        help [options] [<command_name>]Options:
    Options:
        stop      Stop hyper server
        start     Start hyper server
        restart   Restart hyper server
        status    Status hyper server check
        initproxy Init proxy runtime created
        help      Help document
EOF
    return $!
}

case $1 in
  'stop')
    stop
  ;;
  'start')
    start
  ;;
  'restart')
    stop
    start
  ;;
 'status')
    status
  ;;
 'initproxy')
    initproxy
  ;;
  *)
    help
  ;;
esac

exit 0
