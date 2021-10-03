require 'ruby-nfc'
require 'logger'


class Rfid
	@@readers = NFC::Reader.all
	def read_uid
	@@readers[0].poll(IsoDep::Tag, Mifare::Classic::Tag, Mifare::Ultralight::Tag) do |tag|
	begin
		 uid = tag.uid_hex
		return uid
	end
	end
	end
end

if __FILE__ == $0
	puts "Apropi la targeta"
	rf = Rfid.new
	puts rf.read_uid.upcase
		
end
