<?php

use Trojan\Server\Network\BString;
use Trojan\Server\Network\Bytes;

require_once './Network/Bytes.php';
require_once './Network/BString.php';

$binaryData = hex2bin('34313566353261353438623935356166343763313334363538346364306564343862656262613635643433326537383462396662393734660d0a01030569702e6d6500500d0a474554202f20485454502f312e310d0a486f73743a2069702e6d650d0a557365722d4167656e743a206375726c2f372e36382e300d0a4163636570743a202a2f2a0d0a0d0a');

$bytes = Bytes::formBinary($binaryData);

var_dump($bytes[100]);
//
//var_dump(Bytes::formBinary("\r\n"));

$str = new BString($binaryData);
var_dump($str[100]);
// /r /n
$str[100] = 13;
$str[101] = 10;

var_dump($str[100]);

//$unpackData = unpack("C*", $binaryData);
////var_dump($unpackData);
//
//var_dump(bin2hex(pack("C*", ...$unpackData)));
//
////var_dump(count($unpackData));
////var_dump(array_chunk($unpackData, 1));
