<?php

namespace NieFufeng\Express\Enums;

/**
 * 接口指令
 */
enum RequestTypes: int
{
    case EnhancedEdition = 8001; // 增值版
    case StandardEdition = 8002; // 普通版（快递查询）
    case MapEdition = 8003; // 地图版
}
