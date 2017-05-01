import json
import sys
from watson_developer_cloud import ConversationV1

class WatsonConversation:
    def __init__(self):
        #You need to change this for it to match your account
        self.conv = ConversationV1(
            username=“00000000000000000000000000”,
            password=“0000000000000”,
            version='2016-09-20')
        self.workspace_id=‘03581498519854719857’

    def get_output(self, user_text):
        response = self.conv.message(workspace_id=self.workspace_id,
            message_input={'text': user_text})
        #print response
        output = response['output']['text']
        if output:
            return output[0];
        return "Sorry. I did not understand that. Can you try again?"
    def get_intent(self, user_text):
        response = self.conv.message(workspace_id=self.workspace_id,
            message_input={'text': user_text})
        return response['intents'][0]['intent'];
    def get_time(self, user_text):
        response = self.conv.message(workspace_id=self.workspace_id,
            message_input={'text': user_text})
        for entity in response['entities']:
            if entity['entity'] == "sys-time":
               return entity['value']; 
        

#eample
x = WatsonConversation()
output = x.get_output(sys.argv[1])
output1 = x.get_intent(sys.argv[1])
output2 = x.get_time(sys.argv[1])
#print x.get_time(sys.argv[1])
#print sys.argv[1]
#print sample_test
#output = x.get_output('Can you set an alarm for 10AM tomorrow')
if (output):
    print output
else:
    print "none"
if (output1):
    print output1
else:
    print "none"
if (output2):
    print output2
else:
    print "none"
print sys.argv[1]

