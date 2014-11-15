<?php
class WakeOnLan{
	
	private $strEnderecoMAC;
	private $strBroadCastIP;
	private $intPorta;
	private $arrMagicPacket;
	
	/*
	* Construtor da classe WakeOnLan
	*
	*@param string $enderecoMAC | Endereço MAC da placa de rede em hexadecimal (exemplo  00:00:00:FF:FF:FF)
	*@param string $BroadCastIP | Endereço de broadcast da rede, exemplo: 192.168.1.255
	*@param int $porta | Porta de escuta padrão do Wake on lan (porta 9 UDP)
	*@return void
	*/
	public function __construct($enderecoMAC,$BroadCastIP,$porta=9){
		$this->strEnderecoMAC = $enderecoMAC;
		$this->strBroadCastIP = $BroadCastIP;
		$this->intPorta = $porta;
		$this->magicPacket();
	}
	
	public function magicPacket(){
		$strEnderecoMac =  preg_replace('/(-)|(:)/','',$this->strEnderecoMAC);
		$magicPacket = strtoupper(str_repeat("ff",6).str_repeat($strEnderecoMac,16).str_repeat('00',6));
		$this->arrMagicPacket = array('PACKET_DATA' => hex2bin($magicPacket), 'PACKET_LENGHT'=> strlen($magicPacket));
	}
	
	public function sendPacket(){
		$socket = socket_create(AF_INET,SOCK_DGRAM,SOL_UDP);
		socket_sendto($socket,$this->arrMagicPacket['PACKET_DATA'],$this->arrMagicPacket['PACKET_LENGHT'],0,$this->strBroadCastIP,$this->intPorta);
		socket_close($socket);
	}
}

// Exemplo de instanciação da classe

$wakeOnlan = new WakeOnLan('FF:FF:FF:FF:FF:FF','10.255.255.255');
$wakeOnlan->sendPacket();
