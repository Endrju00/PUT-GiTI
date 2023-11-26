counter = 0

function bot21whatTo(agent, actorKnowledge, time)
    counter = counter + 1
end

function bot21onStart(agent, actorKnowledge, time)
    io.write("My name is: ", actorKnowledge:getName(), "\n")
    hello()
end