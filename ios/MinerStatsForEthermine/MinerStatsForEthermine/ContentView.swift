//
//  ContentView.swift
//  MinerStatsForEthermine
//
//  Created by George King on 29/03/2021.
//

import SwiftUI

struct ContentView: View {
    @State private var selection = 0
 
    var body: some View {
        TabView(selection: $selection){
            Text("First View")
                .font(.title)
                .tabItem {
                    VStack {
                        Text("First")
                    }
                }
                .tag(0)
            Text("Second View")
                .font(.title)
                .tabItem {
                    VStack {
                        Text("Second")
                    }
                }
                .tag(1)
            Text("Third View")
            .font(.title)
            .tabItem {
                VStack {
                    Text("Third")
                }
            }
            .tag(2)
        }
    }
}

struct ContentView_Previews: PreviewProvider {
    static var previews: some View {
        ContentView()
    }
}
